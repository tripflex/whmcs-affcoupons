'use strict';
module.exports = function (grunt) {

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		core: {
			css: 'assets/css/core',
			js: 'assets/js/core'
		},
		vendor: {
			js: 'assets/js/vendor',
			css: 'assets/css/vendor'
		},
		build: {
			js: 'assets/js/build',
			css: 'assets/css/build'
		},
		min: {
			css: 'assets/css',
			js: 'assets/js'
		},

		watch: {
			options: {

			},
			js: {
				files: [ '<%= core.js %>/*.js', '<%= vendor.js %>/*.js', '<%= core.js %>/**/*.js', '<%= core.js %>/**/**/*.js' ],
				tasks: ['concat', 'cssmin', 'uglify']
			},
			css: {
				files: [ '<%= core.css %>/*.css', '<%= vendor.css %>/*.css' ],
				tasks: ['concat', 'cssmin', 'uglify']
			},
			less: {
				files: [ '<%= core.css %>/*.less' ],
				tasks: ['less']
			}
		},

		less: {
			core: {
				options: {
					paths: ["<%= core.css %>"],
					cleancss: true
				},
				files: {
					"<%= core.css %>/style.css": "<%= core.css %>/style.less"
				}
			}
		},

		concat: {
			corecss: {
				options: {
					stripBanners: true,
					banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' +
					'<%= grunt.template.today("yyyy-mm-dd") %> */'
				},
				src: [
					'<%= core.css %>/*.css'
				],
				dest: '<%= build.css %>/<%= pkg.acronym %>.css'
			},
			corejs: {
				src: [
					'<%= core.js %>/*.js',
					'<%= core.js %>/**/*.js'

				],
				dest: '<%= build.js %>/<%= pkg.acronym %>.js'
			},
			vendorcss: {
				options: {
					stripBanners: true,
					banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' +
					'<%= grunt.template.today("yyyy-mm-dd") %> */'
				},
				src: [
					'<%= vendor.css %>/*.css',
					'<%= vendor.css %>/**/*.css'
				],
				dest: '<%= build.css %>/vendor.css'
			},
			vendorjs: {
				src: [
					'<%= vendor.js %>/*.js',
					'<%= vendor.js %>/**/*.js'
				],
				dest: '<%= build.js %>/vendor.js'
			}
		},

		cssmin: {
			core: {
				src: '<%= concat.corecss.dest %>',
				dest: '<%= min.css %>/<%= pkg.acronym %>.min.css'
			},
			vendor: {
				src: '<%= concat.vendorcss.dest %>',
				dest: '<%= min.css %>/vendor.min.css'
			}
		},

		uglify: {
			vendor: {
				options: {
					preserveComments: 'none',
					compress: {
						drop_console: true,
						global_defs: {
							"DEBUG": false
						}
					}
				},
				files: {
					'<%= min.js %>/vendor.min.js': ['<%= concat.vendorjs.dest %>']
				}
			},
			core: {
				options: {
					preserveComments: 'none',
					compress: {
						drop_console: true,
						global_defs: {
							"DEBUG": false
						}
					}
				},
				files: {
					'<%= min.js %>/<%= pkg.acronym %>.min.js': ['<%= concat.corejs.dest %>']
				}
			}
		},

		addtextdomain: {
			options: {
				textdomain: '<%= pkg.name %>'    // Project text domain.
			}, target: {
				files: {
					src: [ 'dist/<%= pkg.version %>/<%= pkg.name %>/*.php', 'dist/<%= pkg.version %>/<%= pkg.name %>/**/*.php' ]
				}
			}
		},

		copy: {
			deploy: {
				src: [
					'**', '!Gruntfile.js',
					'!dist/**',
					'!package.json',
					'!node_modules/**',
					'!includes/**/node_modules/**',
					'!includes/**/Gruntfile.js',
					'!includes/**/package.json',
					'!assets/**/build/**',
					'!assets/**/core/**',
					'!assets/**/vendor/**',
					'!includes/**/assets/**/build/**',
					'!includes/**/assets/**/core/**',
					'!includes/**/assets/**/vendor/**',
				],
				dest: 'dist/<%= pkg.version %>/<%= pkg.name %>',
				expand: true
			}
		},

		clean: {
			deploy: {
				src: [ 'dist/<%= pkg.version %>/<%= pkg.name %>' ]
			}
		},

		compress: {
			main: {
				options: {
					archive: 'dist/<%= pkg.name %>_<%= pkg.version %>.zip'
				},
				expand: true,
				cwd: 'dist/<%= pkg.version %>/<%= pkg.name %>/',
				dest: '<%= pkg.name %>',
				src: ['**/**']
			}
		},

		replace: {
			deploy: {
				options: {
					patterns: [
						{
							match: 'timestamp',
							replacement: '<%= grunt.template.today() %>'
						},
						{
							match: 'version',
							replacement: '<%= pkg.version %>'
						},
						{
							match: 'package',
							replacement: '<%= pkg.package %>'
						},
						{
							match: 'link',
							replacement: '<%= pkg.link %>'
						},
						{
							match: 'title',
							replacement: '<%= pkg.title %>'
						}
					]
				}, files: [
					{
						expand: true,
						flatten: false,
						src: [ '*.php', '**/*.php', '!node_modules/**/*.php', '!dist/**/*.php' ],
						dest: 'dist/<%= pkg.version %>/<%= pkg.name %>/'
					}
				]
			}
		}
	});

	grunt.loadNpmTasks('grunt-shell');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-wp-i18n');
	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-replace');

	grunt.registerTask('default', ['less']);

	grunt.registerTask('zipit', ['compress']);

	grunt.registerTask('all', ['less', 'concat', 'cssmin', 'uglify']);

	grunt.registerTask('css', [ 'less:core', 'concat:corecss', 'cssmin:core', 'concat:vendorcss', 'cssmin:vendor' ]);

	grunt.registerTask('vendor', ['concat:vendorcss', 'concat:vendorjs', 'cssmin:vendor', 'uglify:vendor']);
	grunt.registerTask('core', ['less:core','concat:corecss', 'concat:corejs', 'cssmin:core', 'uglify:core']);

	grunt.registerTask('deploy', ['less', 'concat', 'cssmin', 'uglify', 'clean:deploy', 'copy:deploy', 'compress', 'replace:deploy']);


};