module.exports = function(grunt) {

  /////////////////////////////////////////////////////////////////////////
  grunt.initConfig({
    pkg: grunt.file.readJSON("package.json"),
    cfg: grunt.file.readJSON("config.json"),
    webDir:"../",
    availabletasks: {
      tasks: {
        options: {
          sort: true,
          filter: "include",
          tasks: ["default","intro","cleanup","speed","compile:images","watch","build","watch:scripts", "compile:scripts", "compile:styles", "watch:styles", "sync"]
        }
      }
    },
    requirejs: {
      compile: {
        options: {
          baseUrl: "<%=cfg.jsDir%>",
          mainConfigFile: "<%=cfg.jsMainFile%>",
          name: "<%=cfg.jsMainName%>",
          out: "<%=cfg.jsMinFile%>"
        }
      }
    },
    compass: {
      compile: {
        options: {
          httpPath: "<%=cfg.baseURL%>",
          sassDir: "<%=cfg.sassDir%>",
          cssDir: "<%=cfg.cssDir%>",
          imagesDir: "<%=cfg.imgDir%>",
          fontsDir: "<%=cfg.fontsDir%>",
          httpStylesheetsPath:"<%=cfg.cssDir%>",
          cacheDir: "<%=localDir%>/.sass-cache",
          outputStyle:"compressed",
          relativeAssets:true,
          lineComments:false,
          raw: "preferred_syntax = :sass\n",
          environment: "production"
        }
      }
    },
    watch: {
      js: {
        files: ["<%=cfg.jsDir%>/**/*.js"],
        tasks: ["compile:scripts"]
      },
      sass: {
        files: ["<%=cfg.sassDir%>/**/*.scss"],
        tasks: ["compile:styles"]
      },
      everything: {
        files: ["<%=cfg.sassDir%>/**/*.scss", "<%=cfg.jsDir%>/**/*.js"],
        tasks: ["compile:scripts", "compile:styles"]
      }
    },
    browserSync: {
      dev: {
        bsFiles: {
          src : ["<%=cfg.cssDir%>/**/*.css", "<%=webDir%>/**/*.php", "<%=webDir%>/**/*.html", "<%=webDir%>/**/*.js"]
        },
        options: {
          host: "<%=cfg.host%>",
          proxy: "http://<%=cfg.host%>/<%=cfg.baseURL%>/"
        }
      }
    },
    svgmin: {
      default: {
        files: [{
          expand: true,
          cwd: "<%=cfg.imgSrcDir%>",
          src: ['**/*.svg'],
          dest: "<%=cfg.imgDir%>"
        }]
      }
    },
    imagemin: {
      default: {
        files: [{
          expand: true,
          cwd: "<%=cfg.imgSrcDir%>",
          src: ["**/*.{png,jpg,gif}"],
          dest: "<%=cfg.imgDir%>"
        }]
      }
    },
    grunticon: {
      default: {
        files: [{
          expand: true,
          cwd:"<%=cfg.iconsDir%>",
          src: ['*.svg', '*.png'],
          dest: "<%=cfg.imgDir%>/icons"
        }],
        options: {
          datasvgcss: "icons.css",
          datapngcss: "icons.png.css",
          previewhtml: "icons.preview.html"
        }
      }
    },
    clean: {
      options: { 
        force: true 
      },
      default: {
        src: "<%=cfg.cleanFiles%>"
      }
    },   
    autoprefixer: {
      options: {
       browsers: ["last 2 version"]
     },
     default: {
       files: [{
        expand: true, 
        cwd: "<%=cfg.cssDir%>/",
      src: "{,*/}*.css",
      dest: "<%=cfg.cssDir%>/"
    }]
  }
}
});

  /////////////////////////////////////////////////////////////////////////
  grunt.loadNpmTasks("grunt-available-tasks");
  grunt.loadNpmTasks("grunt-contrib-watch");
  grunt.loadNpmTasks("grunt-contrib-requirejs");
  grunt.loadNpmTasks("grunt-contrib-compass");
  grunt.loadNpmTasks("grunt-browser-sync");
  grunt.loadNpmTasks("grunt-contrib-imagemin");
  grunt.loadNpmTasks("grunt-svgmin");
  grunt.loadNpmTasks("grunt-grunticon");
  grunt.loadNpmTasks("grunt-contrib-clean");
  grunt.loadNpmTasks("grunt-autoprefixer");

  /////////////////////////////////////////////////////////////////////////
  grunt.registerTask("default", "These help instructions",["availabletasks"]);
  grunt.registerTask("cleanup", "Clean project",["clean:default"]);
  grunt.registerTask("watch:scripts", "Watch and compile js files",["watch:js"]);
  grunt.registerTask("watch:all", "Watch all (scripts + styles)",["watch:everything"]);
  grunt.registerTask("watch:styles", "Compile sass files",["watch:sass"]);
  grunt.registerTask("compile:scripts", "Compile js files",["requirejs:compile"]);
  grunt.registerTask("compile:styles", "Watch and compile sass files",["compass:compile","autoprefixer"]);
  grunt.registerTask("build", "Build all (scripts + styles)",["compile:styles","compile:scripts", "cleanup"]);
  grunt.registerTask("compile:images", "Optimize images and icons",["imagemin:default", "svgmin:default", "grunticon:default"]);
  grunt.registerTask("sync", "Sync browser",["browserSync:dev"]);
};