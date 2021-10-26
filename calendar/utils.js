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
function getWeekNumber(year, month, date){
    let myweeks = getWeeksInMonth(year, month);
    for (let i = 0; i < myweeks.length; i++) {
        if(myweeks[i].includes(date)){
            return i;
        }
    }
}

// the ugly but stable code lmao
function getWeeksToRender(){
    let weekNumber_before = manipulate.weekNumber - 1;
    let month_before = manipulate.month-1;
    let weekNumber_after = manipulate.weekNumber + 1;
    let month_after = manipulate.month;
    let year_before = manipulate.year;
    let year_after = manipulate.year;

    if (weekNumber_before < 0) {
        month_before -= 1;
        weekNumber_before = getWeeksInMonth(manipulate.year, month_before).length - 1;
    }

    if (weekNumber_after > getWeeksInMonth(manipulate.year, month_after).length-1) {
        month_after += 1;
        weekNumber_after = 0;
    }

    if(manipulate.month == 0){
        year_before-=1;
        month_before=11;
        weekNumber_before = getWeeksInMonth(year_before, month_before).length - 1;
    }
    if(manipulate.month == 11){
        year_after+=1;
        month_after=0;
        weekNumber_after = 0;
    }

    let weeks_to_render_before = getWeeksInMonth(year_before, month_before)[weekNumber_before];
    let weeks_to_render = getWeeksInMonth(manipulate.year, manipulate.month)[manipulate.weekNumber];
    let weeks_to_render_after = getWeeksInMonth(year_after, month_after)[weekNumber_after];

    return weeks_to_render = {
        before: weeks_to_render_before,
        current: weeks_to_render,
        after: weeks_to_render_after,
    };
}

function getWeeksToRender_prev() {
    manipulate.weekNumber -= 1;
    let weekNumber_before = manipulate.weekNumber - 1;
    let month_before = manipulate.month;
    let weekNumber_after = manipulate.weekNumber + 1;
    let month_after = manipulate.month;
    let year_before = manipulate.year;
    let year_after = manipulate.year;

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
        manipulate.year -=1;
        year_before-=1;
        manipulate.month = 11;
    }

    let weeks_to_render_before = getWeeksInMonth(year_before, month_before)[weekNumber_before];
    let weeks_to_render = getWeeksInMonth(manipulate.year, manipulate.month)[manipulate.weekNumber];
    let weeks_to_render_after = getWeeksInMonth(year_after, month_after)[weekNumber_after];

    return weeks_to_render = {
        before: weeks_to_render_before,
        current: weeks_to_render,
        after: weeks_to_render_after,
    };
}

function getWeeksToRender_next() {
    manipulate.weekNumber += 1;
    let weekNumber_before = manipulate.weekNumber - 1;
    let month_before = manipulate.month;
    let weekNumber_after = manipulate.weekNumber + 1;
    let month_after = manipulate.month;
    let year_before = manipulate.year;
    let year_after = manipulate.year;

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
        manipulate.year+=1;
        year_after+=1;
        manipulate.month = 0;
    }
    let weeks_to_render_before = getWeeksInMonth(year_before, month_before)[weekNumber_before];
    let weeks_to_render = getWeeksInMonth(manipulate.year, manipulate.month)[manipulate.weekNumber];
    let weeks_to_render_after = getWeeksInMonth(year_after, month_after)[weekNumber_after];
    return weeks_to_render = {
        before: weeks_to_render_before,
        current: weeks_to_render,
        after: weeks_to_render_after,
    };
}

function getWeeksToRender_today(){
    manipulate.year = date.getFullYear();
    manipulate.month = date.getMonth();
    manipulate.weekNumber = week_number;
    let weekNumber_before = week_number - 1;
    let month_before = date.getMonth()-1;
    let weekNumber_after = week_number + 1;
    let month_after = date.getMonth();

    if (weekNumber_before < 0) {
        month_before -= 1;
        weekNumber_before = getWeeksInMonth(date.getFullYear(), month_before).length - 1;
    }

    if (weekNumber_after > getWeeksInMonth(date.getFullYear(), month_after).length-1) {
        month_after += 1;
        weekNumber_after = 0;
    }

    let weeks_to_render_before = getWeeksInMonth(date.getFullYear(), month_before)[weekNumber_before];
    let weeks_to_render = getWeeksInMonth(date.getFullYear(), date.getMonth())[week_number];
    let weeks_to_render_after = getWeeksInMonth(date.getFullYear(), month_after)[weekNumber_after];

    return weeks_to_render = {
        before: weeks_to_render_before,
        current: weeks_to_render,
        after: weeks_to_render_after,
    };
}

// for clicking dates in year view
function highlightstuff(){
    let year_small_box = document.querySelectorAll('.small_box');
    let year_small_box_2 = document.querySelectorAll('.small_box-2');
    year_small_box.forEach(small_box => {
        small_box.addEventListener('mouseup', setActive);
    });
    year_small_box_2.forEach(small_box_2 => {
        small_box_2.addEventListener('mouseup', setActive);
    });
}

function setActive(){    
    let activated = document.querySelectorAll('.active');
    if(activated.length > 0){
        activated.forEach(active =>{
            active.className = active.className.replace(" active", "");
        });
    }
    this.classList.add('active');
    let day = this.getAttribute('data-day');
    let month  = this.getAttribute('data-month');
    let year = this.getAttribute('data-year');
    let mydate = new Date(year, month, day);
    document.querySelector('#date').innerHTML = mydate;
}

async function getDummyData(){
    return (await fetch('dummy_source.php')).json();
}

function week(){
    let week_box = document.querySelectorAll('.long_box');
    let month_box = document.querySelectorAll('.box');
    week_box.forEach(box=>{
        box.addEventListener('mouseup', addShedule_view);
    });

    month_box.forEach(box=>{
        box.addEventListener('mouseup', addShedule_view);
    });
}

async function addShedule_view(){
    let modal = document.querySelector('.add_sched_modal');
    let modal_content = document.querySelector('.modal_content');
    
    modal.style.display = 'block';
    
    let html = await fetchHTMLFiles('add_schedule.html');
    let parser = new DOMParser();
    let doc = parser.parseFromString(html, 'text/html');
    
    modal_content.innerHTML = doc.querySelector('body').innerHTML;

    let close_modal = document.querySelector('#close_modal');
    close_modal.addEventListener('mouseup', ()=>{
    modal.style.display = 'none';
    });

    let clicked_date = this.getAttribute('data-month') + '-' + this.getAttribute('data-day') + '-' + this.getAttribute('data-year'); 
    let mydate = new Date(clicked_date);

    document.querySelector('task_preview').innerHTML = await putData(mydate);
    
    document.querySelector("#clicked_date").innerHTML = clicked_date;
    document.querySelector("form[name='schedule'] input[name='start_date']").value = formatDate(clicked_date);
    document.querySelector("form[name='schedule'] input[name='end_date']").min = formatDate(clicked_date);

    let myform = document.querySelector("form[name='schedule']");
    let submit_btn = document.querySelector("#submit_btn");
    submit_btn.addEventListener("mouseup", async () => {
    
    let formdata = new FormData(myform);
    formdata.append("date", clicked_date);
    
    let post_response = await fetchPOSTData('add_schedule.php', formdata);
    console.log(post_response);
    });
}

async function fetchHTMLFiles(src){
    let res = await fetch(src);
    let data = await res.text();
    return data;
}

async function fetchPOSTData(src, formData){
    let res = await fetch(src, {method: "post", body:formData})
    let data = await res.text();
    return data;
}

async function putData(mydate) {
    var collection = await getDummyData();
    let html = "";
    collection.forEach((element) => {
        let dueDate = element.dueDate;
        let dueTime = element.dueTime;
        // https:stackoverflow.com/questions/6525538/convert-utc-date-time-to-local-date-time
        var date = new Date(dueDate.month.toString() + "/" + dueDate.day.toString() + "/" + dueDate.year.toString() + " " + 
            (dueTime.hours == null ? "00" : dueTime.hours.toString()) + ":" +
            (dueTime.minutes == null ? "00" : dueTime.minutes.toString()) + " UTC" );

        let hours = date.getHours() == "0" ? "00" : date.getHours();
        let minutes = date.getMinutes() == "0" ? "00" : date.getMinutes();

        if (dueDate.day == mydate.getDate() && dueDate.month == mydate.getMonth() + 1 && dueDate.year == mydate.getFullYear()) {
            html += "<b>" + element.courseName + "</b><br>";
            html += element.title + "| Due date: " + dueDate.month + "/" + dueDate.day + "/" + dueDate.year +
                " | Due time: " + timeConvert(hours + ":" + minutes) +
                "<br>" +
                "Description: " +element.description +
                "<br>" +
                "Link: " + "<a href=" + element.alternateLink + ">course work link</a>" +
                "<br><br>";
        }
    });
    return html;
}

// https://stackoverflow.com/questions/23593052/format-javascript-date-as-yyyy-mm-dd
function formatDate(date) {
    var d = new Date(date),
        month = "" + (d.getMonth() + 1),
        day = "" + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = "0" + month;
    if (day.length < 2) day = "0" + day;

    return [year, month, day].join("-");
}

// https://stackoverflow.com/questions/13898423/javascript-convert-24-hour-time-of-day-string-to-12-hour-time-with-am-pm-and-no
function timeConvert(time) {
    // Check correct time format and split into components
    time = time
        .toString()
        .match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [
        time,
    ];

    if (time.length > 1) {
        // If time format correct
        time = time.slice(1); // Remove full string match value
        time[5] = +time[0] < 12 ? "AM" : "PM"; // Set AM/PM
        time[0] = +time[0] % 12 || 12; // Adjust hours
    }
    return time.join(""); // return adjusted time or original string
}