// year, month, week
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
    current_week_number: getWeekNumber(date.getFullYear(), date.getMonth()),
};

let manipulate = {
    year: date_today.current_year,
    month: date_today.current_month,
    date: date_today.current_date,
    weekNumber: getWeekNumber(date_today.current_year, date_today.current_month),
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
    manipulate.weekNumber = getWeekNumber(date_today.current_year, date_today.current_month);

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
            let weeks_to_render = getWeeksToRender_today();
            renderBoxes_week(weeks_to_render);
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
            let weeks_to_render = getWeeksToRender_prev();
            renderBoxes_week(weeks_to_render);
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
            let weeks_to_render = getWeeksToRender_next();
            renderBoxes_week(weeks_to_render);
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
            let weeks_to_render = getWeeksToRender_today();
            renderBoxes_week(weeks_to_render);
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
                    if (br2 == 7) {
                        html += "<br>";
                        br2 = 0;
                    }
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
        html += '<span class="box-day">' + day[index] + "</span>";
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
                if (br == 7) {
                    html += "<br>";
                    br = 0;
                }
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

function renderBoxes_week(weeks_to_render) {
    let mydate = new Date(date.getFullYear(), weeks_to_render.month);
    let month_name = mydate.toLocaleString("default", { month: "long" });
    date_indication.innerHTML = month_name + "-" + date.getFullYear();
    let html = "";
    let before = weeks_to_render.before;
    let current = weeks_to_render.current;
    let after = weeks_to_render.after;
    let month = weeks_to_render.month;

    let before_size = before.length;
    let current_size = current.length;
    let after_size = after.length;
    let index = 0;

    for (let index = 0; index < day.length; index++) { 
        html += '<span class="long_box-day">' + small_day[index] + "</span>";
    }
    html += '<br>';
    if(current.length < 7){
        if(current[0] == 1){
            while(before_size != 0){
                html += '<span class="long_box-3">'+ before[index] +'</span>';
                before_size-=1;
                index++;
            }
        }
    }
    index = 0;
    while(index != current_size){
        if(current[index] == date.getDate() && month == date.getMonth()){
            html += '<span class="long_box-2">'+ current[index] +'</span>';
            if(index+=1 == RangeError){
                break;
            }else{
                index+=1;
            }
        }
        html += '<span class="long_box">'+ current[index] +'</span>';
        index++;
    }
    index = 0;

    if(current.length < 7){
        if(current[0] > 1){
            while(after_size != 0){
                html += '<span class="long_box-3">'+ after[index] +'</span>';
                after_size-=1;
                index++;
            }
        }
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

// code from https://gist.github.com/markthiessen/3883242
function getWeeksInMonth(year, month) {
    const weeks = [],
      firstDate = new Date(year, month, 1),
      lastDate = new Date(year, month + 1, 0),
      numDays = lastDate.getDate();
  
    let dayOfWeekCounter = firstDate.getDay();
  
    for (let date = 1; date <= numDays; date++) {
      if (dayOfWeekCounter === 0 || weeks.length === 0) {
        weeks.push([]);
      }
      weeks[weeks.length - 1].push(date);
      dayOfWeekCounter = (dayOfWeekCounter + 1) % 7;
    }

    return weeks;
}

// takes year and month, feeds myweeks with the 2D array getWeeksInMonth() returns
// loops myweeks and tries to find what index is the date today and returns what row number it is in the array
function getWeekNumber(year, month){
    let myweeks = getWeeksInMonth(year, month);
    for (let i = 0; i < myweeks.length; i++) {
        if(myweeks[i].includes(date.getDate())){
            return i;
        }
    }
}

function getWeeksToRender_prev() {
    manipulate.weekNumber -= 1;
    let weekNumber_before = manipulate.weekNumber - 1;
    let month_before = manipulate.month;
    let weekNumber_after = manipulate.weekNumber + 1;
    let month_after = manipulate.month;

    if (weekNumber_before < 0) {
        month_before -= 1;
        weekNumber_before = getWeeksInMonth(manipulate.year, month_before).length - 1;
    }

    if (manipulate.weekNumber < 0) {
        manipulate.month -= 1;
        manipulate.weekNumber = getWeeksInMonth(manipulate.year, manipulate.month).length - 1;
    }

    if (weekNumber_after > getWeeksInMonth(manipulate.year, manipulate.month).length-1) {
        month_after += 1;
        weekNumber_after = 0;
    }

    if(manipulate.month < 0){
        manipulate.month = 0;
        manipulate.weekNumber = 0;
    }

    let weeks_to_render_before = getWeeksInMonth(manipulate.year, month_before)[weekNumber_before];
    let weeks_to_render = getWeeksInMonth(manipulate.year, manipulate.month)[manipulate.weekNumber];
    let weeks_to_render_after = getWeeksInMonth(manipulate.year, month_after)[weekNumber_after];

    return weeks_to_render = {
        before: weeks_to_render_before,
        current: weeks_to_render,
        after: weeks_to_render_after,
        month: manipulate.month,
    };
}

function getWeeksToRender_next() {
    manipulate.weekNumber += 1;
    let weekNumber_before = manipulate.weekNumber - 1;
    let month_before = manipulate.month;
    let weekNumber_after = manipulate.weekNumber + 1;
    let month_after = manipulate.month;

    if (weekNumber_before < 0) {
        month_before -= 1;
        weekNumber_before = getWeeksInMonth(manipulate.year, month_before).length - 1;
    }

    if (manipulate.weekNumber > getWeeksInMonth(manipulate.year, manipulate.month).length - 1) {
        manipulate.month += 1;
        manipulate.weekNumber = 0;
    }

    if (weekNumber_after > getWeeksInMonth(manipulate.year, manipulate.month).length-1) {
        month_after += 1;
        weekNumber_after = 0;
    }

    if(manipulate.month > 11){
        manipulate.month = 11;
        manipulate.weekNumber = getWeeksInMonth(manipulate.year, manipulate.month).length - 1
    }

    let weeks_to_render_before = getWeeksInMonth(manipulate.year, month_before)[weekNumber_before];
    let weeks_to_render = getWeeksInMonth(manipulate.year, manipulate.month)[manipulate.weekNumber];
    let weeks_to_render_after = getWeeksInMonth(manipulate.year, month_after)[weekNumber_after];

    return weeks_to_render = {
        before: weeks_to_render_before,
        current: weeks_to_render,
        after: weeks_to_render_after,
        month: manipulate.month,
    };
}

function getWeeksToRender_today(){
    manipulate.month = date_today.current_month;
    manipulate.weekNumber = date_today.current_week_number;
    let weekNumber_before = date_today.current_week_number - 1;
    let month_before = date_today.current_month-1;
    let weekNumber_after = date_today.current_week_number + 1;
    let month_after = date_today.current_month;

    if (weekNumber_before < 0) {
        month_before -= 1;
        weekNumber_before = getWeeksInMonth(date_today.current_year, month_before).length - 1;
    }

    if (weekNumber_after > getWeeksInMonth(date_today.current_year, month_after).length-1) {
        month_after += 1;
        weekNumber_after = 0;
    }

    let weeks_to_render_before = getWeeksInMonth(date_today.current_year, month_before)[weekNumber_before];
    let weeks_to_render = getWeeksInMonth(date_today.current_year, date_today.current_month)[date_today.current_week_number];
    let weeks_to_render_after = getWeeksInMonth(date_today.current_year, month_after)[weekNumber_after];

    return weeks_to_render = {
        before: weeks_to_render_before,
        current: weeks_to_render,
        after: weeks_to_render_after,
        month: date_today.current_month,
    };
}
