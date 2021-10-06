// year, month, week, day
const date = new Date();
const box_range = [12, 42, 7, 1];
let range_holder;
const choose_view = document.querySelector(".choose_view");
const prev = document.querySelector("#prev");
const next = document.querySelector("#next");
const today = document.querySelector("#today");
const date_indication = document.querySelector("#date_indication");

let view_state_holder;
const day = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday",];
const small_day = ["Su", "M", "T", "W", "TH", "F", "Sa",];
const date_today = {
    current_year: date.getFullYear(),
    current_month: date.getMonth(),
    current_day: date.getDay(),
    current_date: date.getDate(),
};

let manipulate = {
    year: date_today.current_year,
    month: date_today.current_month,
};

// initial load out
renderer(choose_view.value);
view_state_holder = choose_view.value;

// event listeners
choose_view.addEventListener("change", go_choose_view);
prev.addEventListener("mouseup", go_prev);
next.addEventListener("mouseup", go_next);
today.addEventListener("mouseup", go_today);

function go_choose_view() {
    console.log("clicked choose view");
    let options = this.value;
    view_state_holder = options;
    // reset every view change
    manipulate.year = date_today.current_year;
    manipulate.month = date_today.current_month;

    renderer(options);
}
// controllers
function renderer(options) {
    switch (options) {
        case "0":
            renderBoxes_year(box_range[options], date_today.current_year);
            break;

        case "1":
            renderBoxes_month(
                box_range[options],
                date_today.current_month,
                date_today.current_year
            );
            break;

        case "2":
            renderBoxes_week(box_range[options], 
                date_today.current_year, 
                date_today.current_month,
                date_today.current_date);
            break;

        case "3":
            renderBoxes_day(box_range[options]);
            break;

        default:
            console.log("no such thing");
            break;
    }
}

function prev_manipulator() {
    switch (view_state_holder) {
        case "0":
            manipulate.year -= 1;
            renderBoxes_year(box_range[view_state_holder], manipulate.year);
            break;

        case "1":
            manipulate.month -= 1;
            if (manipulate.month < 0) {
                manipulate.year -= 1;
                manipulate.month = 11;
            }
            renderBoxes_month(
                box_range[view_state_holder],
                manipulate.month,
                manipulate.year
            );
            break;

        case "2":
            break;

        case "3":
            break;

        default:
            console.log("no such thing");
            break;
    }
}

function next_manipulator() {
    switch (view_state_holder) {
        case "0":
            manipulate.year += 1;
            renderBoxes_year(box_range[view_state_holder], manipulate.year);
            break;

        case "1":
            manipulate.month += 1;
            if (manipulate.month > 11) {
                manipulate.year += 1;
                manipulate.month = 0;
            }
            renderBoxes_month(
                box_range[view_state_holder],
                manipulate.month,
                manipulate.year
            );
            break;

        case "2":
            break;

        case "3":
            break;

        default:
            console.log("no such thing");
            break;
    }
}

function today_manipulator() {
    switch (view_state_holder) {
        case "0":
            manipulate.year = date_today.current_year;
            renderBoxes_year(box_range[view_state_holder], manipulate.year);
            break;

        case "1":
            manipulate.month = date_today.current_month;
            manipulate.year = date_today.current_year;
            renderBoxes_month(box_range[view_state_holder], manipulate.month, manipulate.year);
            break;

        case "2":
            break;

        case "3":
            break;

        default:
            console.log("no such thing");
            break;
    }
}

// renderers
function renderBoxes_year(range, year) {
    date_indication.innerHTML = year;
    let html = "";
    let br = 0;
    let br2 = 0;
    for (let month = 0; month < range; month++) {
        br += 1;
        let mydate = new Date(year, month);
        let month_name = mydate.toLocaleString("default", { month: "long" });
		let daycounter = 0;
		let startofmonth = getDayStartOfMonth(month, year);
		let endofmonth = daysInMonth(month, year);
		let prevmonth = daysInMonth(month - 1, year);
		let excess_fromPrev = prevmonth - startofmonth;
		let startofnextmonth = 0

        html += '<span class="box-4">' +
            	'<span class="month_name">' + month_name + '</span>';

		for (let index = 0; index < day.length; index++) {
			html += '<span class="small_box-no-outline">' + small_day[index] + "</span>";
		}
		
		html += '<div>';
		
        for (let index = 0; index < box_range[1]; index++) {
            br2 += 1;

			while (excess_fromPrev != prevmonth) {
				excess_fromPrev += 1;
				html += '<span class="small_box-3">' + excess_fromPrev + "</span>";
				if (br == 7) {
					html += "<br>";
					br2 = 0;
				}
				index++;
				br2 += 1;
			}

			while (daycounter != endofmonth) {
				daycounter += 1;
				if (daycounter == date.getDate() && year == date.getFullYear() && month == date.getMonth()) {
					html += '<span class="small_box-2">' + daycounter + "</span>";
					daycounter += 1;
					index++;
					br2 += 1;
				}
				html += '<span class="small_box">' + daycounter + "</span>";
				if (br2 == 7) {
					html += "<br>";
					br2 = 0;
				}
				index++;
				br2 += 1;
			}

			startofnextmonth += 1;
        	html += '<span class="small_box-3">' + startofnextmonth + "</span>";
        	if (br2 == 7) {
            	html += "<br>";
            	br2 = 0;
        	}
        }

        html += "</div></span>";
        if (br == 4) {
            html += "<br>";
            br = 0;
        }
    }
    document.querySelector(".container").innerHTML = html;
}

function renderBoxes_month(range, month, year) {
    let mydate = new Date(year, month);
    let month_name = mydate.toLocaleString("default", { month: "long" });
    date_indication.innerHTML = month_name + "-" + year;
    let html = "";
    let br = 0;
    let daycounter = 0;
    let startofmonth = getDayStartOfMonth(month, year);
    let endofmonth = daysInMonth(month, year);
    let prevmonth = daysInMonth(month - 1, year);
    let excess_fromPrev = prevmonth - startofmonth;
    let startofnextmonth = 0;

    for (let index = 0; index < day.length; index++) {
        html += '<span class="box">' + day[index] + "</span>";
    }
    html += "<br>";

    for (let index = 0; index < range; index++) {
        br += 1;
        while (excess_fromPrev != prevmonth) {
            excess_fromPrev += 1;
            html += '<span class="box3">' + excess_fromPrev + "</span>";
            if (br == 7) {
                html += "<br>";
                br = 0;
            }
            index++;
            br += 1;
        }

        while (daycounter != endofmonth) {
            daycounter += 1;
            if (daycounter == date.getDate() && year == date.getFullYear() && month == date.getMonth()) {
                html += '<span class="box2">' + daycounter + "</span>";
                daycounter += 1;
                index++;
                br += 1;
            }
            html += '<span class="box">' + daycounter + "</span>";
            if (br == 7) {
                html += "<br>";
                br = 0;
            }
            index++;
            br += 1;
        }

        startofnextmonth += 1;
        html += '<span class="box3">' + startofnextmonth + "</span>";
        if (br == 7) {
            html += "<br>";
            br = 0;
        }
    }

    document.querySelector(".container").innerHTML = html;
}

function renderBoxes_week(range, year, month, date) {
    let html = "";
    let mydate = new Date(year, month, date);
    let myday = mydate.getDay();
    for (let index = 0; index < day.length; index++) {
        html += '<span class="long_box-day">' + small_day[index] + "</span>";
    }
    html += '<br>';

    for (let index = 0; index < range; index++) {
        html += '<span class="long_box"></span>';
    }
    document.querySelector(".container").innerHTML = html;
}

function renderBoxes_day(range) {
    let html = "";
    for (let index = 0; index < range; index++) {
        html += '<span class="box"></span>';
    }
    document.querySelector(".container").innerHTML = html;
}
// event functions
function go_prev() {
    console.log("clicked prev");
    prev_manipulator();
}

function go_next() {
    console.log("clicked next");
    next_manipulator();
}

function go_today() {
    console.log("clicked today");
    today_manipulator();
}

// date proccessing
function daysInMonth(month, year) {
    return new Date(year, month + 1, 0).getDate();
}

function getDayStartOfMonth(month, year) {
    return new Date(year, month, 1).getDay();
}
