const connectGoogle = document.querySelector("#connect-google");
const connectCanvas = document.querySelector("#connect-canvas");
const canvasToken = document.querySelector("#canvas-token");
const dataResponse = document.querySelector("#get-data-response");
var collection;

documentReady();

async function documentReady() {
    connectCanvas.addEventListener("mouseup", () => {
        dataResponse.innerHTML = "waiting for data...";
        const req = new Request(
            `get_data.php?canvas_token=${canvasToken.value}`
        );

        fetch(req)
            .then((res) => res.text())
            .then(async (data) => {
                try {
                    await checkData(data);
                } catch (error) {
                    dataResponse.innerHTML = "encountered errors...";
                }

            });
    });

    dataResponse.innerHTML = "waiting for data...";

    let data = await get_google_data();
    checkData(data);

    console.log(collection);
}

async function get_google_data() {
    const response = await fetch('get_data.php')
    const data = await response.text();
    return data;
}

async function get_canvas_data() {

}

async function checkData(data) {
    data = await JSON.parse(data);
    collection = data;
    dataResponse.innerHTML = JSON.stringify(collection);
    if (("oauthURL" in collection)) {
        connectGoogle.addEventListener("mouseup", () => {
            window.location.href = collection.oauthURL;
        });
        return;
    }
    connectGoogle.innerHTML = "Revoke Access";
    connectGoogle.addEventListener("mouseup", () => {
        window.location.href = "revoke.php";
    });
};