//
// Made possible with Eric Moellers WebGL 101 Tutorial
// A really cool video on youtube. I watched it. And got started.
//

function GLHelper(gl) {
    "use strict";

    var helper = this;

    if (helper instanceof GLHelper === false) {
        helper = new GLHelper(gl);
    } else {

        if (!window.requestAnimationFrame) GLHelper.shimRequestAnimationFrame();

        helper.rgba = function (r, b, g, a) {
            return new Float32Array([
                r, 0.0, 0.0, a,
                0.0, g, 0.0, a,
                0.0, 0.0, b, a
            ]);
        };
        helper.createProgram = function createProgram(vstr, fstr) {
            var program = gl.createProgram();
            var vshader = helper.createShader(vstr, gl.VERTEX_SHADER);
            var fshader = helper.createShader(fstr, gl.FRAGMENT_SHADER);
            gl.attachShader(program, vshader);
            gl.attachShader(program, fshader);
            gl.linkProgram(program);
            if (!gl.getProgramParameter(program, gl.LINK_STATUS))
                throw gl.getProgramInfoLog(program);
            return program;
        };

        helper.createShader = function createShader(str, type) {
            var shader = gl.createShader(type);
            gl.shaderSource(shader, str);
            gl.compileShader(shader);
            if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS))
                throw gl.getShaderInfoLog(shader);
            return shader;
        };

        helper.screenQuad = function screenQuad() {
            var vertexPosBuffer = gl.createBuffer();
            gl.bindBuffer(gl.ARRAY_BUFFER, vertexPosBuffer);
            var vertices = [-1, -1, 1, -1, -1, 1, 1, 1];
            gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);
            vertexPosBuffer.itemSize = 2; // 2D
            vertexPosBuffer.numItems = 4; // 4*xy
            return vertexPosBuffer;
        };


        helper.screenTriad = function screenTriad() {
            var vertexPosBuffer = gl.createBuffer();
            gl.bindBuffer(gl.ARRAY_BUFFER, vertexPosBuffer);
            var vertices = [-0.5, -0.5, 0.5, -0.5, 0, 0.5 ];
            gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);
            vertexPosBuffer.itemSize = 2;
            vertexPosBuffer.numItems = 3;
            return vertexPosBuffer;
        };


        helper.linkProgram = function (program) {
            var vshader = helper.createShader(program.vshaderSource, gl.VERTEX_SHADER);
            var fshader = helper.createShader(program.fshaderSource, gl.FRAGMENT_SHADER);
            gl.attachShader(program, vshader);
            gl.attachShader(program, fshader);
            gl.linkProgram(program);
            if (!gl.getProgramParameter(program, gl.LINK_STATUS))
                throw gl.getProgramInfoLog(program);
        };

        helper.loadProgram = function (vs, fs, callback) {
            var program = gl.createProgram();

            var vshaderLoader = function (str) {
                program.vshaderSource = str;
                if (program.fshaderSource) {
                    helper.linkProgram(program);
                    callback(program);
                }
            };

            var fshaderLoader = function (str) {
                program.fshaderSource = str;
                if (program.vshaderSource) {
                    helper.linkProgram(program);
                    callback(program);
                }
            };

            helper.loadFile(vs, vshaderLoader, true);
            helper.loadFile(fs, fshaderLoader, true);
            return program;
        };

        helper.loadFile = function (file, callback, noCache, isJson) {
            var request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (request.readyState === 1) {
                    request.send();
                    if (isJson) {
                        request.overrideMimeType("application/json");
                    }
                } else if (request.readyState === 4) {
                    if (request.status === 200) {
                        callback(request.responseText);
                    } else if (request.status === 404) {
                        throw "404 not found: " + file;
                    } else {
                        throw "Unknwon error " + request.status + ": " + file;
                    }
                }
            };
            var url = file;
            if (noCache) {
                url += "?" + (new Date()).getTime();
            }
            request.open("GET", url, true);
        };


        /*
         * Mesh
         *
         */

        helper.Mesh = function Mesh() {
            var mesh = this;
            if (!this instanceof helper.Mesh) {
                mesh = new helper.Mesh();
            } else {

                mesh.load = function (file, callback) {
                    mesh.callback = callback;
                    helper.loadFile(file, function (data) {
                        mesh.init(data);
                    }, false, true);
                };

                mesh.programLoaded = function (program) {
                    program.vertexPositionAttribute = gl.getAttribLocation(program, "aVertexPosition");
                    program.vertexNormalAttribute = gl.getAttribLocation(program, "aVertexNormal");
                    program.vertexTextureCoordAttribute = gl.getAttribLocation(program, "aVertexTextureCoord");
                    program.mMatrixUniform = gl.getUniformLocation(program, "uMMatrix");
                    program.vMatrixUniform = gl.getUniformLocation(program, "uVMatrix");
                    program.pMatrixUniform = gl.getUniformLocation(program, "uPMatrix");
                    program.vDiffuseSampler = gl.getUniformLocation(program, "uDiffuseSampler");
                    program.vEmissiveSampler = gl.getUniformLocation(program, "uEmissiveSampler");

                    if (--mesh.materialsToLoad === 0) {
                        mesh.callback();
                    }

                };

                mesh.loadTex = function (filename) {
                    var tex = gl.createTexture();
                    var img = new Image();
                    img.onload = function () {
                        gl.bindTexture(gl.TEXTURE_2D, tex);
                        gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);
                        gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, img);
                        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
                        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
                        gl.generateMipmap(gl.TEXTURE_2D);
                        gl.bindTexture(gl.TEXTURE0, null);
                    };
                    img.src = filename;
                    return tex;
                };

                mesh.init = function (data) {
                    // json hat vertices, materials (Arrays), numvertices, materal
                    var o = JSON.parse(data);

                    mesh.vertexPosBuffer = gl.createBuffer();
                    gl.bindBuffer(gl.ARRAY_BUFFER, mesh.vertexPosBuffer);
                    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(o.vertexPositions), gl.STATIC_DRAW);

                    mesh.indexBuffer = gl.createBuffer();
                    gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, mesh.indexBuffer);
                    gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(o.vertices), gl.STATIC_DRAW);

                    if (o.vertexNormals) {
                        mesh.vertexNormalBuffer = gl.createBuffer();
                        gl.bindBuffer(gl.ARRAY_BUFFER, mesh.vertexNormalBuffer);
                        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(o.vertexNormals), gl.STATIC_DRAW);
                    }

                    if (o.vertexTextureCoords) {
                        mesh.vertexTextureCoordBuffer = gl.createBuffer();
                        gl.bindBuffer(gl.ARRAY_BUFFER, mesh.vertexTextureCoordBuffer);
                        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(o.vertexTextureCoords), gl.STATIC_DRAW);
                    }
                    mesh.materialsToLoad = o.materials.length;
                    console.log("init", o);
                    mesh.programs = [];

                    o.materials.forEach(function (material) {
                        var prog = helper.loadProgram(
                            material.vertexshader,
                            material.fragmentshader,
                            function (prog) {
                                mesh.programLoaded(prog);
                            }
                        );
                        prog.numvertices = material.numvertices;
                        if (material.diffuse) {
                            prog.diffuseTexture = mesh.loadTex(material.diffuse);
                        }
                        if (material.emissive) {
                            prog.emissiveTexture = mesh.loadTex(material.emissive);
                        }
                        mesh.programs.push(prog);
                    });
                };


                mesh.setMatrixUniforms = function (program) {
                    gl.uniformMatrix4fv(program.mMatrixUniform, false, helper.modelMatrix().d);
                    gl.uniformMatrix4fv(program.pMatrixUniform, false, helper.projectionMatrix().d);
                    gl.uniformMatrix4fv(program.vMatrixUniform, false, helper.viewMatrix().d);
                };


                mesh.draw = function (x) {
                    var start = 0;
                    mesh.programs.forEach(function (program) {
                        gl.useProgram(program);
                        gl.enableVertexAttribArray(program.vertexPositionAttribute);
                        gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, mesh.indexBuffer);
                        gl.bindBuffer(gl.ARRAY_BUFFER, mesh.vertexPosBuffer);
                        gl.vertexAttribPointer(program.vertexPositionAttribute, 3, gl.FLOAT, false, 0, 0);
                        console.log("mesh.programs.forEach", program);

                        if (program.vertexNormalAttribute !== null) {
                            gl.enableVertexAttribArray(program.vertexNormalAttribute);
                            gl.bindBuffer(gl.ARRAY_BUFFER, mesh.vertexNormalBuffer);
                            gl.vertexAttribPointer(program.vertexNormalAttribute, 3, gl.FLOAT, false, 0, 0);
                        }
                        if (program.vertexTextureCoordAttribute !== null) {
                            gl.enableVertexAttribArray(program.vertexTextureCoordAttribute);
                            gl.bindBuffer(gl.ARRAY_BUFFER, mesh.vertexTextureCoordBuffer);
                            gl.vertexAttribPointer(program.vertexTextureCoordAttribute, 2, gl.FLOAT, false, 0, 0);
                        }

                        if (program.diffuseTexture) {
                            gl.activeTexture(gl.TEXTURE0);
                            gl.bindTexture(gl.TEXTURE_2D, program.diffuseTexture);
                            gl.uniform1i(program.uDiffuseSampler, 0);
                        }
                        if (program.emissiveTexture) {
                            gl.activeTexture(gl.TEXTURE1);
                            gl.bindTexture(gl.TEXTURE_2D, program.emissiveTexture);
                            gl.uniform1i(program.uEmissiveSampler, 1);
                        }
                        mesh.setMatrixUniforms(program);
                        gl.drawElements(gl.TRIANGLES, program.numvertices, gl.UNSIGNED_SHORT, start * 2);
                        start += program.numvertices;
                    });
                };
            }
            return mesh;
        };

        /*
         * Matrix4x3
         *
         * Bei den Rigid Body Transforms, die im Tutorial erstmal drankommen
         * ist die 4 by 3 Matrix die bessere Wahl. Sie spart gegenueber der
         * 4 by 4 Matrix allein schon 20 Multiplikationen je Transformation
         *
         */
        helper.Matrix4x3 = function Matrix4x3() {
            this.d = new Float32Array(16);
            // Die Diagonale mit 1 initialisieren
            // damit man es auch so an WebGL weitergeben kann.
            this.d[0] = 1;
            this.d[5] = 1;
            this.d[10] = 1;
            this.d[15] = 1;
        };
        helper.Matrix4x3.prototype = {
            constructor: helper.Matrix4x3,
            make: function (x1, x2, x3, y1, y2, y3, z1, z2, z3, t1, t2, t3) {
                this.d[0] = x1;
                this.d[1] = x2;
                this.d[2] = x3;
                // die 3 wird ausgelassen
                this.d[4] = y1;
                this.d[5] = y2;
                this.d[6] = y3;
                // Achtung, 7
                this.d[8] = z1;
                this.d[9] = z2;
                this.d[10] = z3;
                // und 11 fehlt auch
                this.d[12] = t1;
                this.d[13] = t2;
                this.d[14] = t3;
                // Hier hatte ich mich erst uebel vertan,
                // weil ich einfach arguments[i] zuwies
                return this;
            },

            makeIdentity: function () {
                return this.make(1, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0);
            },

            makeRotate: function (angle, x, y, z) {
                var invlen = 1 / Math.sqrt(x * x + y * y + z * z);
                var n = {
                    x: invlen * x,
                    y: invlen * y,
                    z: invlen * z
                };
                var s = Math.sin(angle);
                var c = Math.cos(angle);
                var t = 1 - c;
                this.d[0] = t * n.x * n.x + c;
                this.d[1] = t * n.x * n.y + s * n.z;
                this.d[2] = t * n.x * n.z - s * n.y;
                this.d[4] = t * n.x * n.y - s * n.z;
                this.d[5] = t * n.y * n.y + c;
                this.d[6] = t * n.y * n.z + s * n.x;
                this.d[8] = t * n.x * n.z + s * n.y;
                this.d[9] = t * n.y * n.z - s * n.x;
                this.d[10] = t * n.z * n.z + c;
                this.d[12] = this.d[13] = this.d[14] = 0;
                return this;
            },
            multiply: function (m) {
                this.make(
                    this.d[0] * m.d[0] + this.d[4] * m.d[1] + this.d[8] * m.d[2],
                    this.d[1] * m.d[0] + this.d[5] * m.d[1] + this.d[9] * m.d[2],
                    this.d[2] * m.d[0] + this.d[6] * m.d[1] + this.d[10] * m.d[2],

                    this.d[0] * m.d[4] + this.d[4] * m.d[5] + this.d[8] * m.d[6],
                    this.d[1] * m.d[4] + this.d[5] * m.d[5] + this.d[9] * m.d[6],
                    this.d[2] * m.d[4] + this.d[6] * m.d[5] + this.d[10] * m.d[6],

                    this.d[0] * m.d[8] + this.d[4] * m.d[9] + this.d[8] * m.d[10],
                    this.d[1] * m.d[8] + this.d[5] * m.d[9] + this.d[9] * m.d[10],
                    this.d[2] * m.d[8] + this.d[6] * m.d[9] + this.d[10] * m.d[10],

                    this.d[0] * m.d[12] + this.d[4] * m.d[13] + this.d[8] * m.d[14] + this.d[12],
                    this.d[1] * m.d[12] + this.d[5] * m.d[13] + this.d[9] * m.d[14] + this.d[13],
                    this.d[2] * m.d[12] + this.d[6] * m.d[13] + this.d[10] * m.d[14] + this.d[14]
                );
                return this;
            },
            makeInverseRigidBody: function (m) {
                var it0 = -m.d[12];
                var it1 = -m.d[13];
                var it2 = -m.d[14];
                // trnsl
                this.d[12] = m.d[0] * it0 + m.d[1] * it1 + m.d[2] * it2;
                this.d[13] = m.d[4] * it0 + m.d[5] * it1 + m.d[6] * it2;
                this.d[14] = m.d[8] * it0 + m.d[9] * it1 + m.d[10] * it2;
                // rott
                this.d[0] = m.d[0];
                this.d[1] = m.d[4];
                this.d[2] = m.d[8];

                this.d[4] = m.d[1];
                this.d[5] = m.d[5];
                this.d[6] = m.d[9];

                this.d[8] = m.d[2];
                this.d[9] = m.d[6];
                this.d[10] = m.d[10];
                return this;
            }
        };

        /*
         * Matrix4x4
         *
         */

        helper.Matrix4x4 = function () {
            this.d = new Float32Array(16);
            this.d[0] = 1;	// x1
            this.d[5] = 1;      // y2
            this.d[10] = 1;	// z3
            this.d[15] = 1;     // t4
            // == Identity == this.make(1,0,0,0, 0,1,0,0, 0,0,1,0, 0,0,0,1);
        };
        helper.Matrix4x4.prototype = {
            constructor: helper.Matrix4x4,
            make: function (x1, x2, x3, x4, y1, y2, y3, y4, z1, z2, z3, z4, t1, t2, t3, t4) {
                // short for assigning all arguments
                for (var i = 0; i < 16; i++) {
                    this.d[i] = arguments[i].valueOf();
                }
                return this;
            },
            makeIdentity: function () {
                return this.make(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            },

            makePerspective: function (fovy, aspect, znear, zfar) {
                var top = znear * Math.tan(fovy * Math.PI / 360.0);
                var bottom = -top;
                var left = bottom * aspect;
                var right = top * aspect;
                var X = 2 * znear / (right - left);
                var Y = 2 * znear / (top - bottom);
                var A = (right + left) / (right - left);
                var B = (top + bottom) / (top - bottom);
                var C = -(zfar + znear) / (zfar - znear);
                var D = -2 * zfar * znear / (zfar - znear);
                this.make(X, 0, 0, 0, 0, Y, 0, 0, A, B, C, -1, 0, 0, D, 0);
                return this;
            }
        };

        helper.globalMatrixState = {
            modelMatrix: [new helper.Matrix4x3(), new helper.Matrix4x3()],
            projectionMatrix: new helper.Matrix4x4().makePerspective(45, 1, 0.01, 100),
            viewMatrix: new helper.Matrix4x3(),
            modelStackTop: 0
        };

        helper.viewMatrix = function () {
            return helper.globalMatrixState.viewMatrix;
        };
        helper.projectionMatrix = function () {
            return helper.globalMatrixState.projectionMatrix;
        };
        helper.modelMatrix = function () {
            return helper.globalMatrixState.modelMatrix[helper.globalMatrixState.modelStackTop];
        };
        helper.pushModelMatrx = function () {
            var state = helper.globalMatrixState;
            state.modelStackTop += 1;
            if (state.modelStackTop === state.modelMatrix.length)
                state.modelMatrix[state.modelMatrix.length] = new Matrix4x3();
            var top = state.modelMatrix[state.modelStackTop];
            var parent = state.modelMatrix[state.modelStackTop - 1];
            for (var j = 0; j < 16; ++j) {
                top.d[j] = parent.d[j];
            }
            return top;
        };
        helper.popModelMatrix = function () {
            helper.globalMatrixState.modelStackTop -= 1;
        };
    }


    /*
     * vec2, vec3, vec4
     */

    helper.dot = function (u, v) {
        var scalar;
        scalar = u.x * v.x + u.y * v.y + z.x * z.y;
        return scalar;
    };
    helper.cross = function (u, v) {
        // u2*v3 - u3*v2
        // u3*v1 - u1*v3
        // u1*v2 - u2*v1
        return new helper.Vec3(
            u.y * v.z - u.z * v.y,
            u.z * v.x - u.x * v.z,
            u.x * v.y - u.y * v.x
        );
    };
    helper.min = function () {
    };
    helper.max = function () {
    };

    // Diese Vector Klasse habe ich irgendwo im Internet gelesen,
    // auf irgendeiner Slide, die mit gl-matrix.js und Performance zu tun hatte,
    // und schnell selbst zusammengeschustert. Link und Quelle suche ich
    // spaeter raus. 

    helper.Vec3 = function (x, y, z) {
        var vec3 = this;
        if (!this instanceof helper.Vec3) {
            vec3 = new helper.Vec3(x, y, z);
        } else {
            vec3.xyz = new Float32Array(3);

            vec3.set = function (x, y, z) {
                if (typeof x === "object" && typeof x.x !== "undefined") {
                    vec3.x = x.x;
                    vec3.y = x.y;
                    vec3.z = x.z;
                } else {
                    vec3.x = x;
                    vec3.y = y;
                    vec3.z = z;
                }
                return vec3;
            };

            vec3.set(x, y, z); // setze constructor parameter

            vec3.add = function (vec) {
                return new helper.Vec3(this.x + vec.x, this.y + vec.y, this.z + vec.z);
            };
            vec3.addSelf = function (vec) {
                this.x += vec.x;
                this.y += vec.y;
                this.z += vec.z;
            };
            vec3.sub = function (vec) {
                return new helper.Vec3(this.x - vec.x, this.y - vec.y, this.z - vec.z);
            };
            vec3.subSelf = function (vec) {
                this.x -= vec.x;
                this.y -= vec.y;
                this.z -= vec.z;
            };
            vec3.scale = function (vec) {
                return new helper.Vec3(this.x * vec.x, this.y * vec.y, this.z * vec.z);
            };
            vec3.scaleSelf = function (vec) {
                this.x *= vec.x;
                this.y *= vec.y;
                this.z *= vec.z;
            };

            vec3.length = function (vec) {
                return Math.sqrt((this.x * this.x) + (this.y * this.y) + (this.z * this.z));
            };

            vec3.normalize = function (vec) {
                var invlen = 1 / this.length();
                return new helper.Vec3(this.x * invlen, this.y * invlen, this.z * invlen);
            };
        }
        return vec3;
    };
    helper.Vec3.prototype = Object.create(null,
        {
            "x": {
                get: function () {
                    return this.xyz[0];
                },
                set: function (x) {
                    this.xyz[0] = x;
                }
            },
            "y": {
                get: function () {
                    return this.xyz[1];
                },
                set: function (y) {
                    this.xyz[1] = y;
                }
            },
            "z": {
                get: function () {
                    return this.xyz[2];
                },
                set: function (z) {
                    this.xyz[2] = z;
                }
            }
        }
    );

    helper.Vec2 = function (x, y) {
        var vec2 = this;
        if (!this instanceof helper.Vec2) {
            vec2 = new helper.Vec2(x, y)
        } else {
            vec2.x = x;
            vec2.y = y;
            vec2.set = function (x, y) {
                if (typeof x === "object" && typeof x.x !== "undefined") {
                    vec2.x = x.x;
                    vec2.y = x.y;
                } else {
                    vec2.x = x;
                    vec2.y = y;
                }
                return vec2;
            };
            vec2.add = function (vec) {
                return new helper.Vec2(this.x + vec.x, this.y + vec.y);
            };
            vec2.addSelf = function (vec) {
                this.x += vec.x;
                this.y += vec.y;
            };
            vec2.sub = function (vec) {
                return new helper.Vec2(this.x - vec.x, this.y - vec.y);
            };
            vec2.subSelf = function (vec) {
                this.x -= vec.x;
                this.y -= vec.y;
            };
            vec2.scale = function (vec) {
                return new helper.Vec2(this.x * vec.x, this.y * vec.y);
            };
            vec2.scaleSelf = function (vec) {
                this.x *= vec.x;
                this.y *= vec.y;
            };
            vec2.length = function (vec) {
                return Math.sqrt((this.x * this.x) + (this.y * this.y));
            };
            vec2.normalize = function (vec) {
                var invlen = 1 / this.length();
                return new helper.Vec2(this.x * invlen, this.y * invlen);
            };
        }
        return vec2;
    }

    /*

     Ich sollte mir mal die Geometrischen Grundfiguren speichern,
     damit ich die immer wieder verwenden kann.

     */

    helper.texPending = 0; // nur eine Hilfe
    helper.loadTex = function (filename) {
        var tex = gl.createTexture();
        var img = new Image();
        img.onload = function () {
            --helper.texPending;
            if (helper.textPending === 0 && typeof helper.ontextpending === "function") helper.ontexpending();
            gl.bindTexture(gl.TEXTURE_2D, tex);
            gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);
            gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, img);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
            gl.generateMipmap(gl.TEXTURE_2D);
            gl.bindTexture(gl.TEXTURE0, null);
        };
        ++helper.texPending;
        img.src = filename;
        return tex;
    };


    helper.CubeData = function (gl, program) {

        var cubedata = {
            "vertices": [0, 1, 2, 2, 1, 3, 4, 5, 6, 6, 5, 7, 8, 9, 10, 10, 9, 11, 12, 13, 14, 14, 13, 15, 16, 17, 18, 18, 17, 19, 20, 21, 22, 22, 21, 23],
            "vertexPositions": [-0.5, -0.5, 0.5, 0.5, -0.5, 0.5, -0.5, 0.5, 0.5, 0.5, 0.5, 0.5, -0.5, 0.5, 0.5, 0.5, 0.5, 0.5, -0.5, 0.5, -0.5, 0.5, 0.5, -0.5, -0.5, 0.5, -0.5, 0.5, 0.5, -0.5, -0.5, -0.5, -0.5, 0.5, -0.5, -0.5, -0.5, -0.5, -0.5, 0.5, -0.5, -0.5, -0.5, -0.5, 0.5, 0.5, -0.5, 0.5, 0.5, -0.5, 0.5, 0.5, -0.5, -0.5, 0.5, 0.5, 0.5, 0.5, 0.5, -0.5, -0.5, -0.5, -0.5, -0.5, -0.5, 0.5, -0.5, 0.5, -0.5, -0.5, 0.5, 0.5],
            "vertexNormals": [0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 0, -1, 0, 0, -1, 0, 0, -1, 0, 0, -1, 0, -1, 0, 0, -1, 0, 0, -1, 0, 0, -1, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, -1, 0, 0, -1, 0, 0, -1, 0, 0, -1, 0, 0],
            "texCoords": [0, -3, 1, -3, 0, -2, 1, -2, 0, -2, 1, -2, 0, -1, 1, -1, 0, -1, 1, -1, 0, 0, 1, 0, 0, 0, 1, 0, 0, 1, 1, 1, 1, -3, 2, -3, 1, -2, 2, -2, -1, -3, 0, -3, -1, -2, 0, -2],
            "numvertices": 36
        };

        if (gl && program) {

            cubedata.init = function () {

                program.indexBuffer = gl.createBuffer();
                gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, program.indexBuffer);
                gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(cubedata.vertices), gl.STATIC_DRAW);

                program.vertexPositions = gl.createBuffer();
                gl.bindBuffer(gl.ARRAY_BUFFER, program.vertexPositions);
                gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(cubedata.vertexPositions), gl.STATIC_DRAW);

                program.vertexNormals = gl.createBuffer();
                gl.bindBuffer(gl.ARRAY_BUFFER, program.vertexNormals);
                gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(cubedata.vertexNormals), gl.STATIC_DRAW);

                program.texCoords = gl.createBuffer();
                gl.bindBuffer(gl.ARRAY_BUFFER, program.texCoords);
                gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(cubedata.texCoords), gl.STATIC_DRAW);

            };
            //cubedata.enable = function () {
            //};

        }
        return cubedata;
    }

    helper.SquareData = function () {
        return {
            vertices: [ -1, -1, -1, 1, 1, -1, 1, 1 ],
            numvertices: 4
        };
    };

    helper.TriangleData = function () {
        return {

        };
    };

    helper.PyramidData = function () {
    };

    helper.KegelData = function () {
    };

    helper.ZylinderData = function () {

    }

    return helper;
}


GLHelper.getContext = function (canvas, options) {
    var context;
    ["experimental-webgl", "webgl", "moz-webgl", "webkit-3d", "o-webgl", "3d", "o-3d", "ms-webgl", "ms-3d", "webgl-experimental", "moz-3d"].some(function (str) {
        return !!(context = canvas.getContext(str, options));
    });
    return context;
};

GLHelper.shimRequestAnimationFrame = function () {
    var lastTime = 0;
    var vendors = ["ms", "moz", "webkit", "o"];

    for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x] + "RequestAnimationFrame"];
        window.cancelAnimationFrame = window[vendors[x] + "CancelAnimationFrame"];
    }

    if (!window.requestAnimationFrame) {
        window.requestAnimationFrame = function (callback, event) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
            var id = window.setTimeout(function () {
                callback(currTime + timeToCall);
            }, timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };
    }
    if (!window.cancelAnimationFrame) {
        window.cancelAnimationFrame = function (id) {
            return clearTimeout(id);
        };
    }
};





