import pkg from "gulp";
import gulpIf from "gulp-if";
import gulpSass from "gulp-sass";
import dartSass from "sass";
import cssmin from "gulp-cssmin";
import uglify from "gulp-uglify";
import imagemin from "gulp-imagemin";
import concat from "gulp-concat";
import sourcemaps from "gulp-sourcemaps";
import replace from "gulp-replace";
import esbuild from "gulp-esbuild";

const { src, dest, parallel, series, watch } = pkg;
const sass = gulpSass(dartSass);
const isProd = process.env.NODE_ENV === "prod";

/**--------------------------------------------------------------------------------------------------------------
 * CONFIGURACIÓN
 *
 * estas variables son las que definen que hacer con cada
 * archivo JS, según el tratamiento que se necesite dar
 *
 --------------------------------------------------------------------------------------------------------------*/
/**
 * Archivos copiados individual y literalmente de vendors o módulos de Node.
 *
 */
const filesToVendors = [];

const filesToVendorsJs = [];

/**--------------------------------------------------------------------------------------------------------------
 *  FIN CONFIGURACIÓN
 --------------------------------------------------------------------------------------------------------------*/

/**
 * Copia literal de archivos que pertenecen a VENDORS.
 *
 * Tarea encargada de realizar copias exactas de archivos que pertenecen a módulos de NODE
 * o a VENDORS en general que no son instalables desde NODE
 */
function vendorsCopy() {
  if (filesToVendors.length > 0) {
    return src(filesToVendors).pipe(dest("assets/dist/vendors/"));
  }
  return src(".", { allowEmpty: true });
}

/**
 * Copia literal de archivos que pertenecen a VENDORS en carpeta JS/.
 *
 * Tarea encargada de realizar copias exactas de archivos que pertenecen a módulos de NODE
 * o a VENDORS en general que no son instalables desde NODE y se copian en JS/
 */
function vendorsCopyJs() {
  if (filesToVendorsJs.length > 0) {
    return src(filesToVendorsJs).pipe(dest("assets/dist/js/"));
  }
  return src(".", { allowEmpty: true });
}
/**
 * Minificación de archivos JS que están en la carpeta partials/.
 *
 * Tarea encargada de copiar los archivos JS de la carpeta partials a la carpeta distribuida
 * En caso de se producción minifica el archivo sin añadir extensión.
 */
function minJs() {
  return src("assets/js/partials/**.js").pipe(gulpIf(isProd, uglify())).pipe(dest("assets/dist/js/"));
}

function css() {
  return src("assets/sass/style.scss")
    .pipe(gulpIf(!isProd, sourcemaps.init()))
    .pipe(
      sass({
        includePaths: ["node_modules"],
      }).on("error", sass.logError)
    )
    .pipe(concat("all.css"))
    .pipe(replace("../../../../", "../"))
    .pipe(replace("../../../", "../"))
    .pipe(replace("../../", "../"))
    .pipe(gulpIf(!isProd, sourcemaps.write()))
    .pipe(gulpIf(isProd, cssmin()))
    .pipe(dest("assets/dist/css/"));
}

function adminCss() {
  return src("assets/sass/admin.scss")
    .pipe(gulpIf(!isProd, sourcemaps.init()))
    .pipe(
      sass({
        includePaths: ["node_modules"],
      }).on("error", sass.logError)
    )
    .pipe(concat("admin.css"))
    .pipe(replace("../../../../", "../"))
    .pipe(replace("../../../", "../"))
    .pipe(replace("../../", "../"))
    .pipe(gulpIf(!isProd, sourcemaps.write()))
    .pipe(gulpIf(isProd, cssmin()))
    .pipe(dest("assets/dist/css/"));
}

function js() {
  return src("assets/js/main.js") // Tu archivo principal con imports
    .pipe(esbuild({
      bundle: true,
      minify: isProd,
      sourcemap: !isProd,
      outfile: "bundle.js",
      target: ["es2015"],
    }))
    .pipe(dest("assets/dist/js"));
}



function img() {
  return src("assets/img/**/*").pipe(gulpIf(isProd, imagemin())).pipe(dest("assets/dist/img/"));
}

function models() {
  return src('assets/models/**/*').pipe(dest('assets/dist/models'));
}

function fonts() {
  return src("assets/fonts/**/*").pipe(dest("assets/dist/fonts/"));
}
function watchFiles() {
  watch("assets/**/*.scss", series(css));
  watch("assets/js/*.js", series(js));
  watch("assets/js/partials/*.js", series(minJs));
  watch("assets/img/**/*.*", series(img));
}

export { vendorsCopy, vendorsCopyJs, css, adminCss, js, img, minJs, models, fonts, watchFiles };
export let serve = parallel(vendorsCopy, vendorsCopyJs, css, adminCss, js, img, minJs, models, fonts, watchFiles);
export default series(vendorsCopy, vendorsCopyJs, css, adminCss, js, img, models, fonts, minJs);
