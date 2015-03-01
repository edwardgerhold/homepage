/**
 * Created by root on 03.09.14.
 */
var gl;
var shaderProgram;
var triangleVertexBuffer;
function createGLContext(canvas) {
    var names = ["weblgl", "experimental-webgl"];
    var context = null;
    names.some(function (name) {
        try {
            context = canvas.getContext(name);
        } catch (ex){
        }
        return !!context;
    });
    if (context) {
        context.viewportWidth = canvas.width;
        context.viewportHeight = canvas.height;
    } else {
        throw new TypeError("Can´t get WebGL context.");
    }
    return context;
}
function loadShader(type, shaderSource) {
    var shader = gl.createShader(type);
    gl.shaderSource(shader, shaderSource);
    gl.compileShader(shader);
    if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
        alert("Error compiling shader " + gl.getShaderInfoLog(shader));
        gl.deleteShader(shader);
        return null;
    }
    return shader;
}
function setupShaders() {
    var vertexShaderSource = document.getElementById("vshader").textContent;
    var fragmentShaderSource = document.getElementById("fshader").textContent;
    var vertexShader = loadShader(gl.VERTEX_SHADER, vertexShaderSource);
    var fragmentShader = loadShader(gl.FRAGMENT_SHADER, fragmentShaderSource);

    shaderProgram = gl.createProgram();
    gl.attachShader(shaderProgram, vertexShader);
    gl.attachShader(shaderProgram, fragmentShader);
    gl.linkProgram(shaderProgram);

    if (!gl.getProgramParameter(shaderProgram, gl.LINK_STATUS)) {
        alert("Failed to setup shaders");
    }
    gl.useProgram(shaderProgram);
    shaderProgram.vertexPositionAttribute = gl.getAttribLocation(shaderProgram, "aVertexPosition");
    shaderProgram.vertexColorAttribute = gl.getAttribLocation(shaderProgram, "aVertexColor");
    gl.enableVertexAttribArray(shaderProgram.vertexPositionAttribute);
    gl.enableVertexAttribArray(shaderProgram.vertexColorAttribute);
}
function setupBuffers() {
    triangleVertexBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, triangleVertexBuffer);

    var triangleVertices = [
         0.0,  0.5, 0.0,   255,   0,   0, 255,
         0.5, -0.5, 0.0,     0, 255,   0, 255,
        -0.5, -0.5, 0.0,     0,   0, 255, 255
    ];

    var numVertices = 3;
    var vertexSizeInBytes = 3*Float32Array.BYTES_PER_ELEMENT + 4 * Uint8Array.BYTES_PER_ELEMENT;
    var vertexSizeInFloats =  vertexSizeInBytes / Float32Array.BYTES_PER_ELEMENT;


    var buffer = new ArrayBuffer(numVertices * vertexSizeInBytes);
    var positionView = new Float32Array(buffer);
    var colorView = new Uint8Array(buffer);
    var positionOffsetInFloats = 0;
    var colorOffsetInBytes = 12;
    var k = 0;
    for (var i = 0; i < numVertices; i++) {
        positionView[positionOffsetInFloats] = triangleVertices[k];
        positionView[1+positionOffsetInFloats] = triangleVertices[k+1];
        positionView[2+positionOffsetInFloats] = triangleVertices[k+2];
        colorView[colorOffsetInBytes] = triangleVertices[k+3];
        colorView[colorOffsetInBytes+1] = triangleVertices[k+4];
        colorView[colorOffsetInBytes+2] = triangleVertices[k+5];
        colorView[colorOffsetInBytes+3] = triangleVertices[k+6];
        positionOffsetInFloats += vertexSizeInFloats;
        colorOffsetInBytes += vertexSizeInBytes;
        k += 7;
    }

    gl.bufferData(gl.ARRAY_BUFFER, buffer, gl.STATIC_DRAW);
    triangleVertexBuffer.positionSize = 3;
    triangleVertexBuffer.colorSize = 4;
    triangleVertexBuffer.numberOfItems = 3;
}

function draw() {

    gl.viewport(0,0,gl.viewportWidth, gl.viewportHeight);
    gl.clear(gl.COLOR_BUFFER_BIT);

    gl.bindBuffer(gl.ARRAY_BUFFER, triangleVertexBuffer);

    gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, triangleVertexBuffer.positionSize, gl.FLOAT, false, 16, 0);
    gl.vertexAttribPointer(shaderProgram.vertexColorAttribute, triangleVertexBuffer.colorSize, gl.UNSIGNED_BYTE, true, 16, 12);


    gl.drawArrays(gl.TRIANGLES, 0, triangleVertexBuffer.numberOfItems);
}

function startup() {
    var canvas = document.getElementsByTagName("canvas")[0];
    gl = createGLContext(canvas);
    setupShaders();
    setupBuffers();
    gl.clearColor(0.0, 0.3, 0.3, 0.5);
    setTimeout(draw);
}

window.addEventListener("load", startup, false);
