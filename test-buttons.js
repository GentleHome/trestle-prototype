document.addEventListener("DOMContentLoaded", ()=> {
    setButtons();
});

function setButtons(){
    const getCourses = document.querySelector('#get-courses-button');
    getCourses.addEventListener('click', ()=> {
        const textArea = document.querySelector('#get-courses-response');
        const req = new Request(`get_courses.php/?canvas_token=${getCanvasToken()}`)
        const data = fetch(req).then(res => res.text()).then(data => {

            textArea.value = data;
            var parsed = JSON.parse(data);
            console.log(parsed);

        });
    });
}

function getCanvasToken(){
    const canvasToken = document.querySelector('#canvas-token');
    return canvasToken.value;
}