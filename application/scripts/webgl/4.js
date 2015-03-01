
var uniformList = [
    "uLightPosition",
    "uLightDirection",
    "uLightColor",
    "uCutOff",
    "uSpotExponent",
    "uShininess"
];
var attList = [
    "aVertexPosition",
    "aVertexColor",
    "aVertexNormal"
];

var app = new GL.Controller({
    vs: "vs_001",
    fs: "fs_001",
    uniforms: uniformList,
    attributes: attList,
    iframe: true
});




var model = app.addModel();


// create the cube
        /*
         model.normals = [
         0, 0, 1,  // front face
         0, 0, -1, // back face
         -1, 0, 0, // left face
         1, 0, 0,  // right face
         0, 1, 0,  // top face
         0, 1, 0   // bottom face
         ];
         */
model.vertices = [
    0.3, 0.3, 0.3,
    -0.3, 0.3, 0.3,
    -0.3, -0.3, 0.3,
    0.3, -0.3, 0.3,
    0.3, 0.3, -0.3,
    -0.3, 0.3, -0.3,
    -0.3, -0.3, -0.3,
    0.3, -0.3, -0.3,
    -0.3, 0.3, 0.3,
    -0.3, 0.3, -0.3,
    -0.3, -0.3, -0.3,
    -0.3, -0.3, 0.3,
    0.3, 0.3, 0.3,
    0.3, -0.3, 0.3,
    0.3, -0.3, -0.3,
    0.3, 0.3, -0.3,
    0.3, 0.3, 0.3,
    0.3, 0.3, -0.3,
    -0.3, 0.3, -0.3,
    -0.3, 0.3, 0.3,
    0.3, -0.3, 0.3,
    0.3, -0.3, -0.3,
    -0.3, -0.3, -0.3,
    -0.3, -0.3, 0.3
];


 model.colors = [];
 for (var i = 0, j = 4 * 6 * 4; i < j; i++) model.colors.push(rand());


model.indices = [
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

        model.textureSources.push("/js.jpg");
        model.texcoords = [
            0.0, 0.0,
            1.0, 0.0,
            0.0, 1.0,
            1.0, 1.0

        ];
        model.move = function () {
            "use strict";
        };




app.setOnResize();
app.setContextHandlers();
window.addEventListener("load", app.run.bind(app), false);