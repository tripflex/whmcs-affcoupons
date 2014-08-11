'use strict';
module.exports = function ( grunt ) {

	grunt.initConfig(
		{
			pkg: grunt.file.readJSON( 'package.json' ),
			adminarea: {
				css: 'assets/css/admin',
				js: 'assets/js/admin'
			},
			clientarea: {
				css: 'assets/css/client',
				js: 'assets/js/client'
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
					files: [
						'<%= adminarea.js %>/*.js',
						'<%= clientarea.js %>/*.js',
						'<%= vendor.js %>/*.js',
						'<%= adminarea.js %>/**/*.js',
						'<%= clientarea.js %>/**/*.js',
						'<%= vendor.js %>/**/*.js'
					],
					tasks: [ 'concat', 'cssmin', 'uglify' ]
				},
				css: {
					files: [ '<%= adminarea.css %>/*.css', '<%= clientarea.css %>/*.css', '<%= vendor.css %>/*.css' ],
					tasks: [ 'concat', 'cssmin', 'uglify' ]
				},
				less: {
					files: [ '<%= adminarea.css %>/*.less', '<%= clientarea.css %>/*.less' ],
					tasks: [ 'less' ]
				}
			},

			less: {
				admin: {
					options: {
						paths: [ "<%= adminarea.css %>" ],
						cleancss: true
					},
					files: {
						"<%= adminarea.css %>/style.css": "<%= adminarea.css %>/style.less"
					}
				},
				client: {
					options: {
						paths: [ "<%= clientarea.css %>" ],
						cleancss: true
					},
					files: {
						"<%= clientarea.css %>/style.css": "<%= clientarea.css %>/style.less"
					}
				},
				vendor: {
					options: {
						paths: [ "<%= vendor.css %>" ],
						cleancss: true
					},
					files: {
						"<%= vendor.css %>/bs3.css": "<%= vendor.css %>/bs3.less"
					}
				}
			},

			concat: {
				admincss: {
					options: {
						stripBanners: true,
						banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' +
						        '<%= grunt.template.today("yyyy-mm-dd") %> */'
					},
					src: [
						'<%= adminarea.css %>/*.css'
					],
					dest: '<%= build.css %>/adminarea.css'
				},
				adminjs: {
					src: [
						'<%= adminarea.js %>/*.js',
						'<%= adminarea.js %>/**/*.js'

					],
					dest: '<%= build.js %>/adminarea.js'
				},
				clientcss: {
					options: {
						stripBanners: true,
						banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' +
						        '<%= grunt.template.today("yyyy-mm-dd") %> */'
					},
					src: [
						'<%= clientarea.css %>/*.css'
					],
					dest: '<%= build.css %>/clientarea.css'
				},
				clientjs: {
					src: [
						'<%= clientarea.js %>/*.js',
						'<%= clientarea.js %>/**/*.js'

					],
					dest: '<%= build.js %>/clientarea.js'
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
				admin: {
					src: '<%= concat.admincss.dest %>',
					dest: '<%= min.css %>/adminarea.min.css'
				},
				client: {
					src: '<%= concat.clientcss.dest %>',
					dest: '<%= min.css %>/clientarea.min.css'
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
						'<%= min.js %>/vendor.min.js': [ '<%= concat.vendorjs.dest %>' ]
					}
				},
				admin: {
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
						'<%= min.js %>/adminarea.min.js': [ '<%= concat.adminjs.dest %>' ]
					}
				},
				client: {
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
						'<%= min.js %>/clientarea.min.js': [ '<%= concat.clientjs.dest %>' ]
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
						'!includes/**/assets/**/admin/**',
						'!includes/**/assets/**/client/**',
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
					src: [ '**/**' ]
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
			},

			autoprefixer: {
				options: {
					browsers: [
						'Android 2.3',
						'Android >= 4',
						'Chrome >= 20',
						'Firefox >= 24', // Firefox 24 is the latest ESR
						'Explorer >= 8',
						'iOS >= 6',
						'Opera >= 12',
						'Safari >= 6'
					]
				},
				core: {
					options: {
						map: true
					},
					src: 'dist/css/<%= pkg.name %>.css'
				},
				theme: {
					options: {
						map: true
					},
					src: 'dist/css/<%= pkg.name %>-theme.css'
				},
				docs: {
					src: 'docs/assets/css/_src/docs.css'
				},
				examples: {
					expand: true,
					cwd: 'docs/examples/',
					src: [ '**/*.css' ],
					dest: 'docs/examples/'
				}
			}
		}
	);

	require( 'load-grunt-tasks' )( grunt );

	grunt.registerTask( 'default', [ 'less' ] );

	grunt.registerTask( 'zipit', [ 'compress' ] );

	grunt.registerTask( 'all', [ 'less', 'concat', 'cssmin', 'uglify' ] );

	grunt.registerTask( 'css', [ 'less:admin', 'less:client', 'concat:admincss', 'concat:clientcss', 'cssmin:admin', 'cssmin:client', 'concat:vendorcss', 'cssmin:vendor' ] );

	grunt.registerTask( 'vendor', [ 'concat:vendorcss', 'concat:vendorjs', 'cssmin:vendor', 'uglify:vendor' ] );
	grunt.registerTask( 'admin', [ 'less:admin', 'concat:admincss', 'concat:adminjs', 'cssmin:admin', 'uglify:admin' ] );
	grunt.registerTask( 'client', [ 'less:client', 'concat:clientcss', 'concat:clientjs', 'cssmin:client', 'uglify:client' ] );

	grunt.registerTask( 'deploy', [ 'less', 'concat', 'cssmin', 'uglify', 'clean:deploy', 'copy:deploy', 'compress', 'replace:deploy' ] );

};