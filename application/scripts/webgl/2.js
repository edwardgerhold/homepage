
var loadcount = 0;
var texToLoad = 1;
var stack = [];
var r = 1;
var g = 0.5;
var b = 0.1;
var a = 1;


function setLinearFilter() {
    gl.texParameter(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
    gl.texParameter(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
}

function generateMipMap(texture) {
    gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, texture);
    gl.generateMipmap(gl.TEXTURE_2D);
}

function setMipmapFilter(l, r) {
    var t = {
        "L": "LINEAR",
        "N": "NEAREST"
    };
    gl.texParameter(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl[t[l] + "_MIPMAP_" + t[r]]);
    gl.texParameter(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl[t[l] + "_MIPMAP_" + t[r]]);
}

function setWrapRepeat() {
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.REPEAT);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.REPEAT);
}

function setWrapClampToEdge() {
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
}


function createTexture(gl, src, ctor, draw) {
    ctor = ctor || "image";
    var tex = gl.createTexture();
    var texImage;

    switch (ctor) {
        case "image":
            texImage = new Image();
            break;
        case "video":
            texImage = document.createElement("video");
            break;
        case "file":
            texImage = new Image();
            //src = (new FileReader).readAsArrayBuffer(src.files[0])
            src = null;
            break;
    }

    var el = document.getElementById("texture");
    if (el) {
        el.innerHTML = "";
        el.appendChild(texImage);
    }

    var ev = "load";
    ++loadcount;
    texImage.addEventListener(ev, function (e) {
        --loadcount;
        gl.bindTexture(gl.TEXTURE_2D, tex);
        gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);
        gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, texImage); // upload texture
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.NEAREST);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.NEAREST);
        gl.generateMipmap(gl.TEXTURE_2D);
        gl.bindTexture(gl.TEXTURE0, null);
        if (loadcount == 0) {
            program.requestId = requestAnimationFrame(draw, 1000 / 60);
            if (texImage instanceof HTMLVideoElement) {
                texImage.volume = 0;
                texImage.play();
            }
        }
    }, false);
    texImage.src = src;
    return tex;
}


var glhelp = function (gl) {

    function createShader(id, type) {
        var elem = document.getElementById(id);
        var shader;
        if (elem) {
            var source = elem.textContent;
        } else {
            alert("Can´t locate shader script by id: " + id);
            return null;
        }
        if ((type !== gl.VERTEX_SHADER) && (type !== gl.FRAGMENT_SHADER)) {
            alert("Can´t read v-shader or f-shader of the type attribute.")
            return null;
        }
        shader = gl.createShader(type);
        gl.shaderSource(shader, source);
        gl.compileShader(shader);
        if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
            alert(gl.getShaderInfoLog(shader));
            return null;
        }
        return shader;
    }


    function createProgram(vshader, fshader) {
        var program = gl.createProgram();
        gl.attachShader(program, vshader);
        gl.attachShader(program, fshader);
        gl.linkProgram(program);
        if (!gl.getProgramParameter(program, gl.LINK_STATUS)) {
            alert(gl.getProgramInfoLog(program));
            return null;
        }
        gl.useProgram(program);
        return program;
    }

    function createShaderProgram() {
        return createProgram(
            createShader("vshader", gl.VERTEX_SHADER),
            createShader("fshader", gl.FRAGMENT_SHADER)
        );
    }

    var glhelp = {
        createShader: createShader,
        createProgram: createProgram,
        createShaderProgram: createShaderProgram
    };

    return glhelp;

};


function rand() {
    return Math.floor(Math.random() * 10) / 10; // 0.0 .. 0.3
}


var uMVMatrix, uMMatrix, uVMatrix, uPMatrix, uNMatrix;
var program, vshader, fshader;
var canvas, gl;

function setupBuffers() {
    "use strict";
    var cubeVertexNormals = [
        0, 0, 1, // front face
        0, 0, -1, // back face
        -1, 0, 0,      // left face
        1, 0, 0, // right face
        0, 1, 0, // top face
        0, 1, 0 // bottom face
    ];
    var triangleVertexPosition = [
        1, 0, 0,
        0, 1, 0,
        0, 0, 1,
        -1, 0, 0,
        0, -1, 0,
        0, 0, -1,
        0, 0, 1,
        0, 1, 0,
        1, 0, 0,
        0, 0, -1,
        0, -1, 0,
        0, 0, -1
    ];
    var cubeVertexPosition = [
// Front face
        0.3, 0.3, 0.3, //v0
        -0.3, 0.3, 0.3, //v1
        -0.3, -0.3, 0.3, //v2
        0.3, -0.3, 0.3, //v3
// Back face
        0.3, 0.3, -0.3,
        -0.3, 0.3, -0.3,
        -0.3, -0.3, -0.3,
        0.3, -0.3, -0.3, //v4
// Left face
        -0.3, 0.3, 0.3,
        -0.3, 0.3, -0.3,
        -0.3, -0.3, -0.3,
        -0.3, -0.3, 0.3, //v8
// Right face
        0.3, 0.3, 0.3,
        0.3, -0.3, 0.3,
        0.3, -0.3, -0.3,
        0.3, 0.3, -0.3,
// Top face
        0.3, 0.3, 0.3, //v16
        0.3, 0.3, -0.3, //v17
        -0.3, 0.3, -0.3, //v18
        -0.3, 0.3, 0.3, //v19
// Bottom face
        0.3, -0.3, 0.3,
        0.3, -0.3, -0.3,
        -0.3, -0.3, -0.3,
        -0.3, -0.3, 0.3
    ];
    var cubeVertexColors = [
    ];
    for (var i = 0, j = 4 * 6 * 4; i < j; i++) {
        cubeVertexColors.push(rand());
    }
    var cubeVertexIndices = [
        0, 1, 2,
        0, 2, 3, // Front face
        4, 6, 5,
        4, 7, 6, // Back face
        8, 9, 10,
        8, 10, 11, // Left face
        12, 13, 14,
        12, 14, 15, // Right face
        16, 17, 18,
        16, 18, 19, // Top face
        20, 22, 21,
        20, 23, 22 // Bottom face
    ];
    var cubeTextureCoords = [
        0.0, 1.0,
        1.0, 1.0,
        0.0, 0.0,
        1.0, 0.0
    ];
    var triangleVertexPositionBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, triangleVertexPositionBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(triangleVertexPosition), gl.STATIC_DRAW);
    triangleVertexPositionBuffer.itemSize = 3;
    triangleVertexPositionBuffer.numItems = 4;


    var cubeVertexColorBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexColorBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Uint16Array(cubeVertexColors), gl.STATIC_DRAW);
    cubeVertexColorBuffer.itemSize = 4;
    cubeVertexColorBuffer.numItems = 24;

    var cubeVertexPositionBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexPositionBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(cubeVertexPosition), gl.STATIC_DRAW);
    cubeVertexPositionBuffer.itemSize = 3;
    cubeVertexPositionBuffer.numItems = 24;

    var cubeVertexIndexBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, cubeVertexIndexBuffer);
    gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint8Array(cubeVertexIndices), gl.STATIC_DRAW);
    cubeVertexIndexBuffer.itemSize = 1;
    cubeVertexIndexBuffer.numItems = 36;


    var cubeVertexTextureBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexTextureBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(cubeTextureCoords), gl.STATIC_DRAW);
    cubeVertexTextureBuffer.itemSize = 2;
    cubeVertexTextureBuffer.numItems = 4;


    var cubeVertexNormalBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexNormalBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(cubeVertexNormals), gl.STATIC_DRAW);
    cubeVertexNormals.itemSize = 3;
    cubeVertexNormals.numItems = 6;

    program.vertexPositionAttribute = gl.getAttribLocation(program, "aVertexPosition"); // vertexposition
    program.textureCoordAttribute = gl.getUniformLocation(program, "aTextureCoord"); // textur
    program.vertexColorAttribute = gl.getAttribLocation(program, "aVertexColor");   // farben

    if (!(program.modelViewMatrixUniform = gl.getUniformLocation(program, "uMVMatrix"))) {   // modelview
        program.modelMatrixUniform = gl.getUniformLocation(program, "uMMatrix");
        program.viewMatrixUniform = gl.getUniformLocation(program, "uVMatrix");
    }
    program.projectionMatrixUniform = gl.getUniformLocation(program, "uPMatrix");   // projection (camera)

    if (!program.vertexColorAttribute) {
        program.samplerUniform = gl.getUniformLocation(program, "uSampler");            // textur statt farbe
    }

    program.diffuseLightUniform = gl.getUniformLocation(program, "uDiffuseLight");
    program.diffuseMaterialUniform = gl.getUniformLocation(program, "uDiffuseMaterial");
    program.lightPositionUniform = gl.getUniformLocation(program, "uLightPosition");
    program.normalMatrixUniform = gl.getUniformLocation(program, "uNMatrix");       // licht

}

function setupShaders() {
    "use strict";
    program = glhelp(gl).createShaderProgram();
}
function setupCanvas() {
    "use strict";

    canvas = document.getElementById("canvas");
    gl = canvas.getContext("webgl");
    if (!gl) gl = canvas.getContext("webgl-experimental");
    gl = WebGLDebugUtils.makeDebugContext(gl);
    gl.viewportHeight = canvas.height;
    gl.viewportWidth = canvas.width;
    gl.clearColor(rand(), rand(), rand(), 1);
    gl.viewport(0, 0, gl.viewportWidth, gl.viewportHeight);
    gl.frontFace(gl.CCW);
    gl.enable(gl.CULL_FACE);
    gl.cullFace(gl.BACK);

}

function setupMatrices() {
    "use strict";
    uMVMatrix = mat4.create();
    uPMatrix = mat4.create();
    uMMatrix = mat4.create();
    uVMatrix = mat4.create();
    uNMatrix = mat3.create();
}

function setNearestFilter() {
    gl.texParameter(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.NEAREST);
    gl.texParameter(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.NEAREST);
}



window.addEventListener("load", function () {
    "use strict";

    setupCanvas();
    setupShaders();
    setupBuffers();
    //program.ambientLightUniform = gl.getUniformLocation(program, "uAmbientLight");

    function draw() {
        "use strict";
        gl.clear(gl.COLOR_BUFFER_BIT);

        mat4.rotate(uMVMatrix, uMVMatrix, 0.01, [2, 2, 5]);
        gl.uniformMatrix4fv(program.modelViewMatrixUniform, false, uMVMatrix);
        gl.uniformMatrix4fv(program.projectionMatrixUniform, false, uPMatrix);


        g += 0.001;
        if (g > 1) g = 0;
        gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexPositionBuffer);
        gl.enableVertexAttribArray(program, program.vertexPositionAttribute);
        gl.vertexAttribPointer(program.vertexPositionAttribute, cubeVertexPositionBuffer.itemSize, gl.FLOAT, false, 0, 0);
        gl.drawElements(gl.LINES, cubeVertexIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);

        /*
         *
         mat4.toInverseMat3(pwgl.modelViewMatrix, normalMatrix);
         mat3.transpose(normalMatrix);
         gl.uniformMatrix3fv(pwgl.uniformNormalMatrixLoc, false, normalMatrix);
         *
         */

        if (useTexture) {

            gl.enableVertexAttribArray(program.textureCoordAttribute);
            gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexTextureBuffer);
            gl.vertexAttribPointer(program.textureCoordAttribute, cubeVertexTextureBuffer.itemSize, gl.UNSIGNED_BYTE, false, 0, 0);
            gl.activeTexture(gl.TEXTURE0);
            gl.uniform1i(program.samplerUniform, 0);
            gl.bindTexture(gl.TEXTURE_2D, texture);
            gl.drawArrays(gl.LINES, 0, cubeVertexTextureBuffer.numItems);

        } else {

            gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexColorBuffer);
            gl.vertexAttribPointer(program.vertexColorAttribute, cubeVertexColorBuffer.itemSize, gl.UNSIGNED_BYTE, false, 0, 0);
            gl.drawArrays(gl.LINES, 0, cubeVertexColorBuffer.numItems);
        }


//        stack.push(mat4.create(uMVMatrix));
        //      mat4.rotate(uMVMatrix, uMVMatrix, 0.7, vec3.create([0.3,.3,2]));
        //  gl.uniformMatrix4fv(program.modelViewMatrixUniform, false, uMVMatrix);
        b += 0.01;
        if (b > 1) b = 0;


        gl.bindBuffer(gl.ARRAY_BUFFER, triangleVertexPositionBuffer);
        gl.vertexAttribPointer(program.vertexPositionAttribute, triangleVertexPositionBuffer.itemSize, gl.FLOAT, false, 0, 0);
        gl.drawArrays(gl.LINES, 0, triangleVertexPositionBuffer.numItems);

        //    uMVMatrix = stack.pop();

        program.requestId = requestAnimationFrame(draw, 1000 / 60);
    }


    // when the image is done, draw starts, call last
    var texture = createTexture(gl, "/js.jpg", "image", draw);
    // var texture = createTexture(gl, "/videos/irgendeins.mp4", "video", draw);
    var useTexture = false;


    var tf = document.getElementById("texFile");
    if (tf) tf.addEventListener("change", function () {
        if (cancelAnimationFrame) {
            cancelAnimationFrame(draw);
        }
        texture = createTexture(gl, this, "file", draw);
    });
    document.addEventListener("keydown", function (e) {
    });
    document.addEventListener("keypress", function (e) {
    });
    document.addEventListener("keyup", function (e) {
    });
    canvas.addEventListener("webglcontextlost", function (e) {
        cancelAnimationFrame(program.requestId);

    }, false);
    canvas.addEventListener("webglcontextrestore", function (e) {
        setupShaders();
        setupBuffers();
        gl.clearColor(1.0, 1.0, 1.0, 1.0);
        program.requestId = requestAnimationFrame(draw, 1000);
    }, false);
}, false);