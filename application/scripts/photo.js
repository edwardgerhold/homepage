
window.addEventListener("DOMContentLoaded", function () {

    var photo = document.querySelector("#photo");
    var photoInfos = document.querySelector("#photoInfos");
    var photoPreview = document.querySelector("#photoPreview");

    if (photo) {
        photo.addEventListener("change", function () {
            getPhotoInfos(photo.files[0])
        }, false);
    }

    function getPhotoInfos(file) {
        if (!(/^(image)/).test(file.type)) {
            photoInfos.innerHTML = "invalid file type, only image/* will be accepted";
            photo.value = "";
        } else {
            var infos = "You are about to upload:<br>";
            var reader = new FileReader();
            reader.onloadend = function () {
                photoPreview.src = this.result;
            };
            reader.readAsDataURL(file);
            infos += "Name: " + file.name + "<br>";
            infos += "Type: " + file.type + "<br>";
            infos += "Size: " + (file.size/1024).toFixed(2) + "kb<br>";
            infos += "Last modified: " + file.lastModifiedDate;
            photoInfos.innerHTML = infos;
        }
    }

}, false);