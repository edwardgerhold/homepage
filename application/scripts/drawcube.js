function setupCubeBuffers() {
cubeVertexPositionBuffer = gl.createBuffer();
gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexPositionBuffer);
var cubeVertexPosition = [
// Front face
1.0, 1.0, 1.0, //v0
-1.0, 1.0, 1.0, //v1
-1.0, -1.0, 1.0, //v2
1.0, -1.0, 1.0, //v3
// Back face
1.0, 1.0, -1.0,
-1.0, 1.0, -1.0,
-1.0, -1.0, -1.0,
1.0, -1.0, -1.0, //v4
//v5
//v6
//v7
// Left face
-1.0, 1.0, 1.0,
-1.0, 1.0, -1.0,
-1.0, -1.0, -1.0,
-1.0, -1.0, 1.0, //v8
//v9
//v10
//v11
// Right face
1.0, 1.0, 1.0,
1.0, -1.0, 1.0,
1.0, -1.0, -1.0,
1.0, 1.0, -1.0,
// Top face
//12
//13
//14
//151.0,
1.0,
-1.0,
-1.0,
1.0, 1.0, //v16
1.0, -1.0, //v17
1.0, -1.0, //v18
1.0, 1.0, //v19
// Bottom face
1.0, -1.0, 1.0,
1.0, -1.0, -1.0,
-1.0, -1.0, -1.0,
-1.0, -1.0, 1.0,
//v20
//v21
//v22
//v23
];
gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(cubeVertexPosition),
gl.STATIC_DRAW);
cubeVertexPositionBuffer.itemSize = 3;
cubeVertexPositionBuffer.numberOfItems = 24;
cubeVertexIndexBuffer = gl.createBuffer();
gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, cubeVertexIndexBuffer);
var cubeVertexIndices = [
0, 1, 2,
0, 2, 3,
// Front face
4, 6, 5,
4, 7, 6,
// Back face
8, 9, 10,
8, 10, 11, // Left face
12, 13, 14,
12, 14, 15, // Right face
16, 17, 18,
16, 18, 19, // Top face
20, 22, 21,
20, 23, 22 // Bottom face
];
gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(cubeVertexIndices),
gl.STATIC_DRAW);
cubeVertexIndexBuffer.itemSize = 1;
cubeVertexIndexBuffer.numberOfItems = 36;
}