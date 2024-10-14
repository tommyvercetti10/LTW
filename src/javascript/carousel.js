var slideIndex = 0;
showImage(slideIndex);

function nextImage() {
    showImage(slideIndex += 1);
}

function previousImage() {
    showImage(slideIndex -= 1);
}

function showImage(n) {
    let images = document.getElementsByClassName("carousel-image");
    if (n < 0) {
        slideIndex = images.length - 1;
    } else if (n >= images.length) {
        slideIndex = 0;
    } else {
        slideIndex = n;
    }
    for (let i = 0; i < images.length; i++) {
        images[i].style.display = "none";
    }
    images[slideIndex].style.display = "block";
}
