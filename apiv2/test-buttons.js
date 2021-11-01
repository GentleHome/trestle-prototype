const connectGoogle = document.querySelector("#connect-google");
const connectCanvas = document.querySelector("#connect-canvas");
const canvasToken = document.querySelector("#canvas-token");
const dataResponse = document.querySelector("#get-data-response");
var collection;

documentReady();

async function documentReady() {
    connectCanvas.addEventListener("mouseup", () => {
        console.log(canvasToken.value);
        const req = new Request(
            `get_data.php?canvas_token=${canvasToken.value}`
        );

        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                console.log(data);
                checkData(data);
            });
    });

    let data = await get_google_data();
    console.log(data);
    checkData(data);

    console.log(collection);
    dataResponse.innerHTML = "<pre>" + JSON.stringify(collection) + "</pre>";


}

async function get_google_data() {
    const response = await fetch('get_data.php')
    const data = await response.text();
    return data;
}

async function get_canvas_data() {

}

async function checkData(data) {
    data = JSON.parse(data);
    collection = data;
    for (let index = 0; index < collection.length; index++) {
        if (("oauthURL" in collection[index])) {
            connectGoogle.addEventListener("mouseup", () => {
                window.location.href = collection[index].oauthURL;
            });
            return;
        }
    }
    connectGoogle.style.display = "none";
};