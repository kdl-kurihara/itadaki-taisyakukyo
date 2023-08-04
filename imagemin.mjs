import * as fs from 'node:fs';

import imagemin from 'imagemin';
import mozjpeg from 'imagemin-mozjpeg';
import pngquant from 'imagemin-pngquant';
import svgo from 'imagemin-svgo';

(async () => {
  const inputPaths = process.argv.filter((arg) =>
    /\.(jpe?g|png|svg)$/.test(arg)
  );
  const plugins = [
    mozjpeg({
      quality: 80,
      progressive: true,
    }),
    pngquant({
      optimizationLevel: 7,
    }),
    svgo({
      plugins: [
        'preset-default',
        {
          name: 'removeAttrs',
          params: { attrs: ['data-name'] },
        },
      ],
    }),
  ];

  for (const inputPath of inputPaths) {
    const output = (await imagemin([inputPath], { plugins }))[0].data;
    console.log(
      `${inputPath}: ${fs.statSync(inputPath).size} byte -> ${
        output.length
      } byte.`
    );
    fs.writeFileSync(inputPath, output);
  }
})().catch((error) => {
  console.error(error.message, error);
  process.exit(1);
});
