define("jscube", [], function () {

    /*
     Dieser Source und die Script Tags mit den Shadern hierueber gehoeren zum JSCube. Ich werde den jetzt auslagern und mit meinem
     Loader "modulejs laden.
     */
    if (document.location.href === "http://127.0.0.1/index.html") return; // mein Rechner ist so lahmarschig...

    var c = document.getElementById("jscube");
    var gl = GLHelper.getContext(c);

    gl.enable(gl.DEPTH_TEST);	// damit ich nicht in den wuerfel gucken kann...
    gl.clearColor(Math.random() * 1.0, Math.random() * 1.0, Math.random() * 1.0, 0.9);
    var helper = GLHelper(gl);

    var numindices = 36;
    var indices = [0, 1, 2, 2, 1, 3, 4, 5, 6, 6, 5, 7, 8, 9, 10, 10, 9, 11, 12, 13, 14, 14, 13, 15, 16, 17, 18, 18, 17, 19, 20, 21, 22, 22, 21, 23];
    var vertices = [-0.5, -0.5, 0.5, 0.5, -0.5, 0.5, -0.5, 0.5, 0.5, 0.5, 0.5, 0.5, -0.5, 0.5, 0.5, 0.5, 0.5, 0.5, -0.5, 0.5, -0.5, 0.5, 0.5, -0.5, -0.5, 0.5, -0.5, 0.5, 0.5, -0.5, -0.5, -0.5, -0.5, 0.5, -0.5, -0.5, -0.5, -0.5, -0.5, 0.5, -0.5, -0.5, -0.5, -0.5, 0.5, 0.5, -0.5, 0.5, 0.5, -0.5, 0.5, 0.5, -0.5, -0.5, 0.5, 0.5, 0.5, 0.5, 0.5, -0.5, -0.5, -0.5, -0.5, -0.5, -0.5, 0.5, -0.5, 0.5, -0.5, -0.5, 0.5, 0.5];
    var normals = [0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 0, -1, 0, 0, -1, 0, 0, -1, 0, 0, -1, 0, -1, 0, 0, -1, 0, 0, -1, 0, 0, -1, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, -1, 0, 0, -1, 0, 0, -1, 0, 0, -1, 0, 0];
    var texCoords = [0, -3, 1, -3, 0, -2, 1, -2, 0, -2, 1, -2, 0, -1, 1, -1, 0, -1, 1, -1, 0, 0, 1, 0, 0, 0, 1, 0, 0, 1, 1, 1, 1, -3, 2, -3, 1, -2, 2, -2, -1, -3, 0, -3, -1, -2, 0, -2];

    function draw() {
        requestAnimationFrame(draw, c);
        gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);
        helper.modelMatrix().makeRotate(z, 0, 0, 1);
        helper.modelMatrix().multiply(rot.makeRotate(z, 1, 0, 0));
        helper.modelMatrix().multiply(rot.makeRotate(z, 0, 1, 0));
        camera.d[14] = 3 + Math.sin(z);
        viewMatrix().makeInverseRigidBody(camera);

        gl.enableVertexAttribArray(program.vertexPosAttrib);
        gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, indexBuffer);
        gl.bindBuffer(gl.ARRAY_BUFFER, vertexPosBuffer);
        gl.vertexAttribPointer(program.vertexPosAttrib, 3, gl.FLOAT, false, 0, 0);

        gl.enableVertexAttribArray(program.vertexNormalAttrib);
        gl.bindBuffer(gl.ARRAY_BUFFER, normalBuffer);
        gl.vertexAttribPointer(program.vertexNormalAttrib, 3, gl.FLOAT, false, 0, 0);

        gl.enableVertexAttribArray(program.vertexTexAttrib);
        gl.bindBuffer(gl.ARRAY_BUFFER, texBuffer);
        gl.vertexAttribPointer(program.vertexTexAttrib, 2, gl.FLOAT, false, 0, 0);

        gl.activeTexture(gl.TEXTURE0);
        gl.bindTexture(gl.TEXTURE_2D, tex);
        gl.uniform1i(program.uDiffuse, 0);

        gl.activeTexture(gl.TEXTURE1);
        gl.bindTexture(gl.TEXTURE_2D, texEm);
        gl.uniform1i(program.uEmissive, 1);

        //color.set(Math.random()*0.5, Math.random()*0.5, Math.random()*0.5);
        //gl.uniform3f(program.uColor, color.x, color.y, color.z);

        gl.uniformMatrix4fv(program.mMatrix, false, helper.modelMatrix().d);
        gl.uniformMatrix4fv(program.vMatrix, false, helper.viewMatrix().d);
        gl.uniformMatrix4fv(program.pMatrix, false, helper.projectionMatrix().d);
        gl.drawElements(gl.TRIANGLES, numindices, gl.UNSIGNED_SHORT, 0);
        z += 0.01;
    };

    // buffer att 0 indexbuffer und vertex
    var vertexPosBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, vertexPosBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);
    var indexBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, indexBuffer);
    gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(indices), gl.STATIC_DRAW);
    // buffer att 1 normale
    var normalBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, normalBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(normals), gl.STATIC_DRAW);

    // buffer att 2 textur
    var texBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, texBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(texCoords), gl.STATIC_DRAW);
    // var loadcount = 0;
    var texloaded = 0;
    var createTex = function (src) {
        var tex = gl.createTexture();
        var texImage = new Image();
        // ++loadcount;
        texImage.addEventListener("load", function (e) {
            ++texloaded;
            // --loadcount;
            gl.bindTexture(gl.TEXTURE_2D, tex);
            gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);
            gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, texImage);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
            gl.generateMipmap(gl.TEXTURE_2D);
            gl.bindTexture(gl.TEXTURE0, null);
            if (texloaded === 2) {
                requestAnimationFrame(draw, c);
            }
        }, false);
        texImage.src = src;
        return tex;
    };

    var tex = createTex("js.jpg");
    var texEm = createTex("jsem.jpg");

    var vs = document.getElementById("jsvs").textContent;
    var fs = document.getElementById("jsfs").textContent;

    var program = helper.createProgram(vs, fs);
    gl.useProgram(program);

    // attributes
    program.vertexPosAttrib = gl.getAttribLocation(program, "aVertexPosition");
    program.vertexNormalAttrib = gl.getAttribLocation(program, "aVertexNormal");
    program.vertexTexAttrib = gl.getAttribLocation(program, "aTexCoord");
    // uniforms
//    program.uColor  = gl.getUniformLocation(program, "uColor");    
    program.mMatrix = gl.getUniformLocation(program, "uMMatrix");
    program.vMatrix = gl.getUniformLocation(program, "uVMatrix");
    program.pMatrix = gl.getUniformLocation(program, "uPMatrix");
    program.uEmissive = gl.getUniformLocation(program, "uEmissive");
    program.uDiffuse = gl.getUniformLocation(program, "uDiffuse");
    // Diese Variabelnamen sind inkonsistent gewaehlt
    // Beim naechsten mal mache ich das anders und gleich

    var rot = new helper.Matrix4x3();
    var camera = new helper.Matrix4x3();
    var z = 0.00;

});

