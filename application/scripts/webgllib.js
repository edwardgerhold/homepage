var floorVertexPositionBuffer;
var floorVertexIndexBuffer;
var cubeVertexPositionBuffer;
var cubeVertexIndexBuffer;

var modelViewMatrix;
var modelViewMatrixStack;
var projectionMatrix;

function pushModelViewMatrix() {
    var copyToPush = mat4.create(modelViewMatrix);
    modelViewMatrixStack.push(copyToPush);
}
function popModelViewMatrix() {
    if (modelViewMatrixStack.length == 0)  {
        throw new TypeError("popModelViewMatrix() stack was empty");
    }
    modelViewMatrix = modelViewMatrixStack.pop();
}