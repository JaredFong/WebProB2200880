/*document.addEventListener('DOMContentLoaded', () => {
    const newTopicButton = document.querySelector('.new-topic');
    const searchButton = document.querySelector('#searchButton');
    const searchInput = document.querySelector('#searchInput');
    homeButton = document.querySelector(".home-button");

    homeButton.addEventListener("click", () => {
        window.location.href = "index.php";
    });

    newTopicButton.addEventListener('click', () => {
        alert('New Topic button clicked!');
        // Implement functionality to start a new thread
    });

    searchButton.addEventListener('click', () => {
        const query = searchInput.value.trim();
        if (query) {
            alert(`Searching for: ${query}`);
            // Implement search functionality
        }
    });
});*/

document.addEventListener('mousemove', (event) => {
    const cursor = document.querySelector('.cursor');
    cursor.style.left = `${event.pageX}px`;
    cursor.style.top = `${event.pageY}px`;
});