document.addEventListener('wheel', (e) => {
    e.preventDefault();
    document.documentElement.scrollLeft += e.deltaY;
});

document.addEventListener('scroll', function() {
    const scrollPosition = window.scrollY;
    const windowHeight = window.innerHeight;
    const maxScroll = document.body.scrollHeight - windowHeight;
    const fade = Math.min(scrollPosition / maxScroll, 1);
    document.querySelector('.background').style.opacity = 1 - fade;
    document.querySelector('.overlay').style.opacity = fade;
});

document.addEventListener('mousemove', (event) => {
    const cursor = document.querySelector('.cursor');
    cursor.style.left = `${event.pageX}px`;
    cursor.style.top = `${event.pageY}px`;
});