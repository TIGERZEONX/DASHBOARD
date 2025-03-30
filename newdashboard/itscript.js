
function loadChapters(courseId) {
    let chapterSelect = document.getElementById('chapterSelect');
    chapterSelect.innerHTML = "<option disabled selected>Loading...</option>";

    fetch('load_chapters.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'course_id=' + courseId
    })
    .then(response => response.text())
    .then(data => {
        chapterSelect.innerHTML = "<option disabled selected>Select Chapter</option>" + data;
    });
}

function loadTopics(chapterId) {
    let topicSelect = document.getElementById('topicSelect');
    topicSelect.innerHTML = "<option disabled selected>Loading...</option>";

    fetch('load_topics.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'chapter_id=' + chapterId
    })
    .then(response => response.text())
    .then(data => {
        topicSelect.innerHTML = "<option disabled selected>Select Topic</option>" + data;
    });
}
