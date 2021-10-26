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

function week(){
    let week_box = document.querySelectorAll('.long_box');
    // let month_box = document.querySelectorAll('.box');
    week_box.forEach(box=>{
        box.addEventListener('mouseup', addShedule_view);
    });

    // month_box.forEach(box=>{
    //     box.addEventListener('mouseup', addShedule_view);
    // });
}

function addShedule_view(){
    let current_dir = window.location.pathname;
    let dir = current_dir.substring(0, current_dir.lastIndexOf('/'))
    let clicked_date = this.getAttribute('data-month') + '-' + this.getAttribute('data-day') + '-' + this.getAttribute('data-year'); 
    document.location.href= dir +'/add_schedule.html?date='+ clicked_date;
        
}
