// CODE sortieren: modelwise. model.draw(), model.setup(), etc. draw() { models.foreach() }

var uMVMatrix, uMMatrix, uVMatrix, uPMatrix, uNMatrix;
var program, vshader, fshader;
var canvas, gl;
var cubeVertexPositionBuffer, cubeVertexIndexBuffer, cubeVertexColorBuffer, cubeVertexTextureBuffer, cubeVertexNormalBuffer;
var cubeVertexNormals, cubeVertexPosition, cubeVertexIndices, cubeVertexColors, cubeVertexTextureCoordinates;
var loadcount = 0;
var texToLoad = 1;
var texture;
var useTexture = false;
var stack = [];
var r = 1.0;
var g = 1.0;
var b = 1.0;
var a = 1.0;


function setLinearFilter() {
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
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
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl[t[l] + "_MIPMAP_" + t[r]]);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl[t[l] + "_MIPMAP_" + t[r]]);
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
        setLinearFilter();
        generateMipMap(texImage);
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

function setupBuffers() {
    "use strict";
    cubeVertexNormals = [
        0.0, 0.0, 1.0,  // front face
        0.0, 0.0, -1.0, // back face
        -1.0, 0.0, 0.0, // left face
        1.0, 0.0, 0.0,  // right face
        0.0, 1.0, 0.0,  // top face
        0.0, 1.0, 0.0   // bottom face
    ];
    cubeVertexPosition = [
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

    cubeVertexColors = [];
    for (var i = 0, j = 4*8; i < j; i+=4) {

        cubeVertexColors.push(rand());
        cubeVertexColors.push(rand());
        cubeVertexColors.push(rand());
        cubeVertexColors.push(1.0);

        cubeVertexColors.push(rand());
        cubeVertexColors.push(rand());
        cubeVertexColors.push(rand());
        cubeVertexColors.push(1.0);

        cubeVertexColors.push(rand()    );
        cubeVertexColors.push(rand());
        cubeVertexColors.push(rand());
        cubeVertexColors.push(1.0);

    }

    cubeVertexIndices = [
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

    cubeVertexTextureCoordinates = [
        0.0, 1.0,
        1.0, 0.0,
        0.0, 0.0,
        1.0, 1.0
    ];
/*
    for (var i = 0; i < 24; i++) {
        cubeVertexTextureCoordinates = cubeVertexTextureCoordinates.concat(cubeVertexTextureCoordinates);
    }
*/

    cubeVertexPositionBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexPositionBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(cubeVertexPosition), gl.STATIC_DRAW);
    cubeVertexPositionBuffer.itemSize = 3;
    cubeVertexPositionBuffer.numItems = 24;
    program.vertexPositionAttribute = gl.getAttribLocation(program, "aVertexPosition"); // vertexposition


    cubeVertexColorBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexColorBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(cubeVertexColors), gl.STATIC_DRAW);
    cubeVertexColorBuffer.itemSize = 4;
    cubeVertexColorBuffer.numItems = 24;
    program.vertexColorAttribute = gl.getAttribLocation(program, "aVertexColor"); // vertexColor


    cubeVertexIndexBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, cubeVertexIndexBuffer);
    gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(cubeVertexIndices), gl.STATIC_DRAW);
    cubeVertexIndexBuffer.itemSize = 1;
    cubeVertexIndexBuffer.numItems = 36;

    cubeVertexTextureBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexTextureBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(cubeVertexTextureCoordinates), gl.STATIC_DRAW);
    cubeVertexTextureBuffer.itemSize = 2;
    cubeVertexTextureBuffer.numItems = 4;
    program.textureCoordinatesAttribute = gl.getAttribLocation(program, "aTextureCoordinates"); // textur

    cubeVertexNormalBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexNormalBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(cubeVertexNormals), gl.STATIC_DRAW);
    cubeVertexNormalBuffer.itemSize = 3;
    cubeVertexNormalBuffer.numItems = 24;
    program.vertexNormalAttribute = gl.getAttribLocation(program, "aVertexNormal"); // vertexposition
    if (!(program.modelViewMatrixUniform = gl.getUniformLocation(program, "uMVMatrix"))) {   // modelview
        program.modelMatrixUniform = gl.getUniformLocation(program, "uMMatrix");
        program.viewMatrixUniform = gl.getUniformLocation(program, "uVMatrix");
    }
    program.projectionMatrixUniform = gl.getUniformLocation(program, "uPMatrix");   // projection (camera)
    if (!program.vertexColorAttribute) {
        program.samplerUniform = gl.getUniformLocation(program, "uSampler");            // textur statt farbe
    }
    program.ambientLightUniform = gl.getUniformLocation(program, "uAmbientLight");
    program.ambientMaterialUniform = gl.getUniformLocation(program, "uAmbientMaterial");
    program.lightPositionUniform = gl.getUniformLocation(program, "uLightPosition");
    program.diffuseLightUniform = gl.getUniformLocation(program, "uDiffuseLight");
    program.diffuseMaterialUniform = gl.getUniformLocation(program, "uDiffuseMaterial");
    program.specularLightUniform = gl.getUniformLocation(program, "uSpecularLight");
    program.specularMaterialUniform = gl.getUniformLocation(program, "uSpecularMaterial");
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
    // gl = WebGLDebugUtils.makeDebugContext(gl);
    gl.viewportHeight = canvas.height;
    gl.viewportWidth = canvas.width;
    gl.clearColor(rand(), rand(), rand(), 1.0);
    gl.viewport(0, 0, gl.viewportWidth, gl.viewportHeight);
    gl.frontFace(gl.CCW);
    gl.enable(gl.CULL_FACE);
    gl.cullFace(gl.BACK);

}

function setupMatrices() {
    "use strict";
    uMVMatrix = mat4.create();
    uPMatrix = mat4.create();
    //mat4.frustum(uPMatrix, 10.0, 10.0, -10.0, 10.0, 0.1, 100.0);
    uMMatrix = mat4.create();
    uVMatrix = mat4.create();
    uNMatrix = mat3.create();
    mat3.normalFromMat4(uNMatrix, uMMatrix);

}

function setNearestFilter() {
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.NEAREST);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.NEAREST);
}

function draw() {
    "use strict";
    gl.clear(gl.COLOR_BUFFER_BIT);


    mat4.rotate(uMMatrix, uMMatrix, 0.01, [1, 1, 1]);
    mat3.normalFromMat4(uNMatrix, uMMatrix);

    gl.uniformMatrix4fv(program.normalMatrixUniform, false, uNMatrix);

    if (program.modelViewMatrixUniform) {

        gl.uniformMatrix4fv(program.modelViewMatrixUniform, false, uMVMatrix);

    } else {

        gl.uniformMatrix4fv(program.modelMatrixUniform, false, uMMatrix);
        gl.uniformMatrix4fv(program.viewMatrixUniform, false, uVMatrix);

    }

    gl.uniformMatrix4fv(program.projectionMatrixUniform, false, uPMatrix);

    gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexPositionBuffer);
    gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, cubeVertexIndexBuffer);
    gl.enableVertexAttribArray(program.vertexPositionAttribute);
    gl.vertexAttribPointer(program.vertexPositionAttribute, cubeVertexPositionBuffer.itemSize, gl.FLOAT, false, 0, 0);

    gl.enableVertexAttribArray(program.vertexColorAttribute);
    gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexColorBuffer);
    gl.vertexAttribPointer(program.vertexColorAttribute, cubeVertexColorBuffer.itemSize, gl.FLOAT, false, 0, 0);

    //gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexNormalBuffer);
    //gl.enableVertexAttribArray(program.vertexNormalAttribute);
   // gl.vertexAttribPointer(program.vertexNormalAttribute, cubeVertexNormalBuffer.itemSize, gl.FLOAT, false, 0, 0);
    // Draw Cube per Positions from Array Buffer via Indices per Element Array Buffer


    if (useTexture) {

        // Draw Textures onto the Cube


//              gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexTextureBuffer);
         gl.enableVertexAttribArray(program.textureCoordinatesAttribute);
         gl.vertexAttribPointer(program.textureCoordinatesAttribute, cubeVertexTextureBuffer.numItems, gl.FLOAT, false, 0, 0);

        gl.activeTexture(gl.TEXTURE0);
        gl.bindTexture(gl.TEXTURE_2D, texture);
        gl.uniform1i(program.samplerUniform, 0);
    }

    gl.drawElements(gl.TRIANGLES, cubeVertexIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);
    program.requestId = requestAnimationFrame(draw, 1000 / 60);
}

function handleKeyup(e) {
}
function handleKeypress(e) {
}
function handleKeydown(e) {
}
function handleContextLost(e) {
    cancelAnimationFrame(program.requestId);
}

function handleContextRestore(e) {
    setupShaders();
    setupBuffers();
    setupTextures();

    gl.clearColor(1.0, 1.0, 1.0, 1.0);
    program.requestId = requestAnimationFrame(draw, 1000);
}

function setupTextures() {
    // when the image is done, draw starts, call lastrit
    texture = createTexture(gl, "/js.jpg", "image", draw);
    // var texture = createTexture(gl, "/videos/irgendeins.mp4", "video", draw);
    useTexture = true;
}

function setupUniforms() {
    gl.uniform3fv(program.ambientLightUniform, [1, 1, 1]);
    gl.uniform3fv(program.ambientMaterialUniform, [1, 1, 1]);
    gl.uniform3fv(program.lightPositionUniform, [2, 2, 2]);
    gl.uniform3fv(program.diffuseLightUniform, [1, 2, 3])
    gl.uniform3fv(program.diffuseMaterialUniform, [1, 3, 5])
    gl.uniform3fv(program.specularLightUniform, [1, 2, 7])
    gl.uniform3fv(program.specularMaterialUniform, [1, 3, 7])

}

window.addEventListener("load", function () {
    "use strict";
    setupCanvas();
    setupShaders();
    setupMatrices();
    setupBuffers();
    //setupTextures();
    setupUniforms();

    document.addEventListener("keydown", handleKeydown);
    document.addEventListener("keypress", handleKeypress);
    document.addEventListener("keyup", handleKeyup);
    canvas.addEventListener("webglcontextlost", handleContextLost, false);
    canvas.addEventListener("webglcontextrestore", handleContextRestore, false);


    if (!useTexture) program.requestId = requestAnimationFrame(draw, 1000 / 60);
}, false);