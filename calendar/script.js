// data variables
var collection = [];

// year, month, week
const date = new Date();
const box_range = [12, 42, 7, 1];
let range_holder;
const choose_view = document.querySelector(".choose_view");
const prev = document.querySelector("#prev");
const next = document.querySelector("#next");
const today = document.querySelector("#today");
const date_indication = document.querySelector("#date_indication");
const the_date = document.querySelector('#date');
let selected_date = new Date(the_date.innerHTML);

let view_state_holder;
const day = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday",];
const small_day = ["Su", "M", "T", "W", "TH", "F", "Sa",];

let week_number = getWeekNumber(date.getFullYear(), date.getMonth(), date.getDate());

let manipulate = {
    year: date.getFullYear(),
    month: date.getMonth(),
    date: date.getDate(),
    weekNumber: getWeekNumber(date.getFullYear(), date.getMonth(), date.getDate()),
};

// initial load out
view_state_holder = choose_view.value;
renderer(view_state_holder);

// event listeners
choose_view.addEventListener("change", go_choose_view);
prev.addEventListener("mouseup", prev_manipulator);
next.addEventListener("mouseup", next_manipulator);
today.addEventListener("mouseup", today_manipulator);

function go_choose_view() {
    let options = this.value;
    view_state_holder = options;

    renderer(view_state_holder);
}
// controllers
async function renderer(options) {
    
    collection = await getDummyData();

    if(the_date.innerHTML){
        selected_date = new Date(the_date.innerHTML);
        manipulate.year = selected_date.getFullYear();
        manipulate.month = selected_date.getMonth();
        manipulate.date = selected_date.getDate();
        manipulate.weekNumber = getWeekNumber(selected_date.getFullYear(), selected_date.getMonth(), selected_date.getDate())
    }

    switch (options) {
        case "0":
            renderBoxes_year(box_range[options], 
                manipulate.year);
            break;

        case "1":
            renderBoxes_month(
                box_range[options],
                manipulate.month,
                manipulate.year
            );
            break;

        case "2":
            let weeks_to_render = getWeeksToRender();
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
    manipulate.year = date.getFullYear();
    manipulate.month = date.getMonth();
    manipulate.date = date.getDate();
    manipulate.weekNumber = getWeekNumber(date.getFullYear(), date.getMonth(), date.getDate());

    the_date.innerHTML='';
    switch (view_state_holder) {
        case "0":
            manipulate.year = date.getFullYear();
            renderBoxes_year(box_range[view_state_holder], manipulate.year);
            break;

        case "1":
            manipulate.month = date.getMonth();
            manipulate.year = date.getFullYear();
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
    selected_date = new Date(the_date.innerHTML);
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
					html += '<span class="small_box-2" data-day="'+daycounter+'" data-month="'+month+'" data-year="'+year+'">' +
                        daycounter + "</span>";
                    if (br2 == 7) {
                        html += "<br>";
                        br2 = 0;
                    }
					daycounter += 1;
					index++;
					br2 += 1;
				}
                if(the_date.innerHTML!= '' && daycounter == selected_date.getDate() && year == selected_date.getFullYear() && month == selected_date.getMonth()){
                    html += '<span class="small_box active" data-day="'+daycounter+'" data-month="'+month+'" data-year="'+year+'">' +
                        daycounter + "</span>";
                }else{
                    html += '<span class="small_box" data-day="'+daycounter+'" data-month="'+month+'" data-year="'+year+'">' +
                    daycounter + "</span>";
                }

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
    highlightstuff();
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
    // rendering sunday to saturday
    for (let index = 0; index < day.length; index++) {
        html += '<span class="box-day">' + day[index] + "</span>";
    }
    html += "<br>";
    // rendering dates with range of 42 boxes
    for (let index = 0; index < range; index++) {
        br += 1;
        // rendering the dates from prev month if theres any
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
        // rendering the dates of the current month
        while (daycounter != endofmonth) {
            daycounter += 1;
            // checks if this is the current date and marks it light green
            if (daycounter == date.getDate() && year == date.getFullYear() && month == date.getMonth()) {
                html += '<span class="box current_day" data-day="'+daycounter+'" data-month="'+ (manipulate.month+1) +'" data-year="'+manipulate.year+'" data-view="week">' + 
                daycounter;

                collection.forEach(data=>{
                    if(data.dueDate.day==daycounter && 
                        data.dueDate.year == manipulate.year &&
                        data.dueDate.month == manipulate.month+1){
                        html+= '<span class="dot"></span>'
                    }
                });

                html+= "</span>";
                if (br == 7) {
                    html += "<br>";
                    br = 0;
                }
                daycounter += 1;
                index++;
                br += 1;
                
            }
            html += '<span class="box" data-day="'+daycounter+'" data-month="'+ (manipulate.month+1) +'" data-year="'+manipulate.year+'" data-view="week">' + 
            daycounter;

            collection.forEach(data=>{
                if(data.dueDate.day==daycounter && 
                    data.dueDate.year == manipulate.year &&
                    data.dueDate.month == manipulate.month+1){
                    html+= '<span class="dot"></span>'
                }
            });

            html+="</span>";
            if (br == 7) {
                html += "<br>";
                br = 0;
            }
            index++;
            br += 1;
        }
        // if theres excess fields for the dates of next month it will be rendered
        startofnextmonth += 1;
        html += '<span class="box3">' + startofnextmonth + "</span>";
        if (br == 7) {
            html += "<br>";
            br = 0;
        }
    }
    document.querySelector(".container").innerHTML = html;
    week();
}

function renderBoxes_week(weeks_to_render) {
    let mydate = new Date(manipulate.year, manipulate.month);
    let month_name = mydate.toLocaleString("default", { month: "long" });
    date_indication.innerHTML = month_name + "-" + manipulate.year;
    let html = "";
    let before = weeks_to_render.before;
    let current = weeks_to_render.current;
    let after = weeks_to_render.after;
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
            while(index != before_size){
                html += '<span class="long_box-3">'+ before[index] +'</span>';
                index++;
            }
        }
    }
    index = 0;
    while(index != current_size){
        if(current[index] == date.getDate() && manipulate.month == date.getMonth() && manipulate.year == date.getFullYear()){
            html += '<span class="long_box current_day" data-day="'+current[index]+'" data-month="'+ (manipulate.month+1) +'" data-year="'+manipulate.year+'" data-view="week">'+ 
            current[index];
            
            collection.forEach(data=>{
                if(data.dueDate.day==current[index] && 
                    data.dueDate.year == manipulate.year &&
                    data.dueDate.month == manipulate.month+1){
                    html+= '<span class="dot"></span>'
                }
            });

            html+='</span>';
        
        }else{
            html += '<span class="long_box" data-day="'+current[index]+'" data-month="'+ (manipulate.month+1) +'" data-year="'+manipulate.year+'" data-view="week">'+
            current[index];

            collection.forEach(data=>{
                if(data.dueDate.day==current[index] && 
                    data.dueDate.year == manipulate.year &&
                    data.dueDate.month == manipulate.month+1){
                    html+= '<span class="dot"></span>'
                }
            });

            html+='</span>';
        }
        index++;
    }
    index = 0;
    if(current.length < 7){
        if(current[0] > 1){
            while(index != after_size){
                html += '<span class="long_box-3">'+ after[index] +'</span>';
                index++;
            }
        }else{
            while (index != 7-(before_size+current_size)) {
                html += '<span class="long_box-3">'+ after[index] +'</span>';
                index++;
            }
        }
    }
    document.querySelector(".container").innerHTML = html;
    week();
}