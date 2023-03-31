// пути
const PATH_BUILD = ['./public_html', './app'];
const PATH_BUILD_HTML = 'public_html/';
const PATH_BUILD_JS = 'public_html/assets/system/js/';
const PATH_BUILD_CSS = 'public_html/assets/system/css/';
const PATH_BUILD_IMG = 'public_html/assets/system/img/';
const PATH_BUILD_FONTS = 'public_html/assets/system/css/fonts/';

const PATH_SRC_HTML = ['app/*.html', '!app/ui-*.html'];
const PATH_SRC_JS = 'app/js/*.js';
const PATH_SRC_CSS = 'app/scss/*.scss';
const PATH_SRC_IMG = 'app/img/**/*.*';
const PATH_SRC_FONTS = 'app/fonts/**/*.*';

const PATH_WATCH_HTML = 'app/**/*.html';
const PATH_WATCH_JS = 'app/js/**/*.js';
const PATH_WATCH_CSS = 'app/scss/**/*.scss';
const PATH_WATCH_IMG = 'app/img/**/*.*';
const PATH_WATCH_FONTS = 'app/fonts/**/*.*';

const PATH_CLEAN = ['./public_html/assets/system/css/*', './public_html/assets/system/js/*', './public_html/assets/system/img/*'];

// Gulp
import gulp from 'gulp';
// сервер для работы и автоматического обновления страниц
import sync from 'browser-sync';
import strip from 'gulp-strip-comments'; // remove comments
import rigger from 'gulp-rigger'; // модуль для импорта содержимого одного файла в другой
import compilerSass from 'sass';
import gulpSass from 'gulp-sass'; // модуль для компиляции SASS (SCSS) в CSS
import autoprefixer from 'gulp-autoprefixer'; // модуль для автоматической установки автопрефиксов
import cleanCss from 'gulp-clean-css'; // плагин для минимизации CSS
import uglify from 'gulp-uglify-es'; // модуль для минимизации JavaScript
import cache from 'gulp-cache'; // модуль для кэширования
import del from 'del'; // плагин для удаления файлов и каталогов
import rename from 'gulp-rename';
import imagemin from 'gulp-imagemin'; // плагин для сжатия PNG, JPEG, GIF и SVG изображений
import gifsicle from 'imagemin-gifsicle';
import mozjpeg from 'imagemin-mozjpeg';
import optipng from 'imagemin-optipng';
import svgo from 'imagemin-svgo';
import notify from 'gulp-notify';
import sourcemaps from 'gulp-sourcemaps';

const browserSync = sync.create();
const sass = gulpSass(compilerSass);

// запуск сервера
gulp.task('browser-sync', () => {
  browserSync.init({
    server: {
      baseDir: PATH_BUILD,
      directory: true
    },
    startPath: "/ui-panel.html",
    notify: false
  })
});

// сбор html
gulp.task('html:build', () => {
  return gulp.src(PATH_SRC_HTML) // выбор всех html файлов по указанному пути
    .pipe(rigger()) // импорт вложений
    .pipe(gulp.dest(PATH_BUILD_HTML)) // выкладывание готовых файлов
    .pipe(browserSync.reload({ stream: true })) // перезагрузка сервера
});

// сбор стилей
gulp.task('css:build', () => {
  return gulp.src(PATH_SRC_CSS) // получим *.scss файлы
    .pipe(sourcemaps.init({ largeFile: true }))
    .pipe(sass({ outputStyle: 'expanded' }).on('error', notify.onError())) // scss -> css
    .pipe(autoprefixer()) // добавим префиксы
    .pipe(gulp.dest(PATH_BUILD_CSS))
    .pipe(rename({ suffix: '.min', prefix: '' }))
    .pipe(cleanCss({ level: { 1: { specialComments: 0 } } })) // минимизируем CSS
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(PATH_BUILD_CSS))
    .pipe(browserSync.stream()) // перезагрузим сервер
});

// сбор js
gulp.task('js:build', () => {
  return gulp.src(PATH_SRC_JS) // получим *.js файлы
    .pipe(rigger()) // импортируем все указанные файлы в *.js
    .pipe(gulp.dest(PATH_BUILD_JS))
    .pipe(rename({ suffix: '.min' }))
    .pipe(uglify.default()) // минимизируем js
    .pipe(strip())
    .pipe(gulp.dest(PATH_BUILD_JS)) // положим готовый файл
    .pipe(browserSync.reload({ stream: true })) // перезагрузим сервер
});

// перенос шрифтов
gulp.task('fonts:build', () => {
  return gulp.src(PATH_SRC_FONTS)
    .pipe(gulp.dest(PATH_BUILD_FONTS))
});

// обработка картинок
gulp.task('image:build', () => {
  return gulp.src(PATH_SRC_IMG)
    .pipe(imagemin([
      gifsicle({ interlaced: true }),
      mozjpeg({ quality: 100, progressive: true }),
      optipng({ optimizationLevel: 5 }),
      svgo()
    ]))
    .pipe(gulp.dest(PATH_BUILD_IMG))
});

// удаление каталога build
gulp.task('clean:build', () => {
  return del(PATH_CLEAN);
});

// очистка кэша
gulp.task('cache:clear', () => {
  cache.clearAll();
});

// сборка
gulp.task('build',
  gulp.series('clean:build',
    gulp.parallel(
      'html:build',
      'css:build',
      'js:build',
      'fonts:build',
      'image:build'
    )
  )
);

// запуск задач при изменении файлов
gulp.task('watch', () => {
  gulp.watch(PATH_WATCH_HTML, gulp.series('html:build'));
  gulp.watch(PATH_WATCH_CSS, gulp.series('css:build'));
  gulp.watch(PATH_WATCH_JS, gulp.series('js:build'));
  gulp.watch(PATH_WATCH_IMG, gulp.series('image:build'));
  gulp.watch(PATH_WATCH_FONTS, gulp.series('fonts:build'));
});

// задача по умолчанию
gulp.task('default', gulp.series(
  'build',
  gulp.parallel('browser-sync', 'watch')
));
