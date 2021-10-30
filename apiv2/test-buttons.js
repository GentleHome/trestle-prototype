
const connectGoogle = document.querySelector("#connect-google");
const connectCanvas = document.querySelector("#connect-canvas");
var collection;

documentReady();

async function documentReady() {

    let data = await get_google_data();

    checkData(data);

    console.log(collection);
}

async function get_google_data() {
    const response = await fetch('get_data.php')
    const data = await response.text();
    return data;
}

async function checkData(data) {
    try {
        data = JSON.parse(data);
        connectGoogle.style.display = "none";
        collection = data;
    } catch (error) {
        connectGoogle.addEventListener("mouseup", () => {
            window.location.href = data;
        });
    }
}