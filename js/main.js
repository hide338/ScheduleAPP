{
  var schedule_array_filmekb = [];
  function changeArray(value) {
    schedule_array_filmekb = [];
    for (var i = 0; i < schedule_array_all.length; i++) {
      if(schedule_array_all[i]["filmekbn"] === "30" || schedule_array_all[i]["filmekbn"] === "31" || schedule_array_all[i]["filmekbn"] === "32" || schedule_array_all[i]["filmekbn"] === "33" || schedule_array_all[i]["filmekbn"] === "34"){
        var filmekbn = "3";
      } else {
        var filmekbn = schedule_array_all[i]["filmekbn"];
      }

      if(filmekbn === value){
        schedule_array_filmekb.push(schedule_array_all[i]);
      }
    }

    if(list_name === "week"){
      createWeekList(schedule_array_filmekb);
    } else if(list_name === "month"){
      createMonthList(schedule_array_filmekb);
    } else {
      createDayList(schedule_array_filmekb);
    }
    // return schedule_array_filmekb;
  }
}

{
  function createDayList(array) {
    var daylist = document.querySelector('.js_daylist');
    var kate_nos = ['①','②','③','④','⑤','⑥','⑦','⑧','⑨','⑩'];
    daylist.innerHTML = "";
    for (var i = 0; i < array.length; i++) {
      var schedule = array[i];
      var tr = document.createElement("tr")
      var td_link = document.createElement("td");
      var td_sttime = document.createElement("td");
      var td_sckb = document.createElement("td");
      var td_kancd = document.createElement("td");
      var td_kjnam = document.createElement("td");
      var td_sctitle = document.createElement("td");
      var td_sijidr = document.createElement("td");
      var td_tantonam = document.createElement("td");
      var td_ngkb = document.createElement("td");

      var sttime = schedule["sttime"].trim();
      if(sttime == "" || sttime == "0000"){
        sttime = "";
      } else if(schedule["filmekbn"] === "11" && sttime.substring(0, 3) === "233"){
        var no = sttime.substring(3);
        sttime = kate_nos[no];
      } else {
        var sttime_hh = sttime.substring(0, 2);
        var sttime_mm = sttime.substring(2);
        var sttime = sttime_hh + ":" + sttime_mm;
      }

      if(schedule["ordno"]){
        var ordno = schedule["ordno"];
      } else {
        var ordno = "";
      }

      if(schedule["syno"]){
        var syno = schedule["syno"];
      } else {
        var syno = "";
      }

      if(schedule["sjtrei"]){
        var sjtrei = schedule["sjtrei"];
      } else {
        var sjtrei = "";
      }

      var url_param = 'details.php?ymd=' + schedule["ymd"] + '&kancd=' + schedule["kancd"] + '&ordno=' + ordno + '&syno=' + syno + '&filmekbn=' + schedule["filmekbn"] + '&sjtrei=' + sjtrei;
      // td_link.innerHTML = '<a><img class="el_icon" src="./img/folder.png" alt=""></a>';
      td_link.innerHTML = '<a href="' + url_param +'"><img class="el_icon" src="./img/folder.png" alt=""></a>';
      td_sttime.innerHTML = sttime;
      // td_sttime.innerHTML = schedule["sttime"];
      td_sckb.innerHTML = schedule["sckb"];
      td_kancd.innerHTML = schedule["kancd"];
      td_kjnam.innerHTML = schedule["kjnam"];
      td_sctitle.innerHTML = schedule["sctitle"];
      // td_sijidr.innerHTML = schedule["sijidr"];
      // td_tantonam.innerHTML = schedule["tantonam"];
      if(schedule["filmekbn"] == "0"){
        td_sijidr.innerHTML = "";
        td_tantonam.innerHTML = schedule["tantonam"];
      } else if(schedule["filmekbn"] == "11"){
        td_sijidr.innerHTML = schedule["tantonam"];
        td_tantonam.innerHTML = "";
      } else {
        td_sijidr.innerHTML = schedule["tantonam"];
        td_tantonam.innerHTML = "";
      }
      td_ngkb.innerHTML = schedule["ngkb"];

      switch(schedule["filmekbn"]) {
        case "11":
          tr.classList.add("bl_categorycolor__fkb11");
          break;
        case "0":
          tr.classList.add("bl_categorycolor__fkb0");
          break;
        case "30":
        case "31":
        case "32":
          tr.classList.add("bl_categorycolor__fkb30-32");
          break;
        case "33":
        case "34":
          tr.classList.add("bl_categorycolor__fkb33-34");
          break;
        case "36":
          tr.classList.add("bl_categorycolor__fkb36");
          break;
        case "14":
          tr.classList.add("bl_categorycolor__fkb14");
          break;
        case "44":
          tr.classList.add("bl_categorycolor__fkb44");
          break;
        case "51":
          tr.classList.add("bl_categorycolor__fkb51");
          break;
        default:
          tr.classList.add("bl_categorycolor__gray");
      }
  
      tr.appendChild(td_link);
      tr.appendChild(td_sttime);
      tr.appendChild(td_sckb);
      tr.appendChild(td_kancd);
      tr.appendChild(td_kjnam);
      tr.appendChild(td_sctitle);
      tr.appendChild(td_sijidr);
      tr.appendChild(td_tantonam);
      tr.appendChild(td_ngkb);
      daylist.appendChild(tr);
    }
    return daylist;
  }
}

{
  function createWeekList(array) {
    var weeklists = document.querySelectorAll('.js_weeklist');
    var kate_nos = ['①','②','③','④','⑤','⑥','⑦','⑧','⑨','⑩'];
    var week_sckbs = [{"filmekbn": "0", "sckb": "オペ"}, {"filmekbn": "11", "sckb": "カテ"}, {"filmekbn": "14", "sckb": "機器"}, {"filmekbn": "30", "sckb": "上内"}, {"filmekbn": "31", "sckb": "下内"}, {"filmekbn": "32", "sckb": "気管内"}, {"filmekbn": "33", "sckb": "ERCP"}, {"filmekbn": "34", "sckb": "胃婁"}, {"filmekbn": "36", "sckb": "病状"}, {"filmekbn": "44", "sckb": "ペ"}, {"filmekbn": "51", "sckb": "化学"}];
    for (var i = 0; i < weeklists.length; i++ ){
      weeklists[i].innerHTML = "";
      for (var j = 0; j < array.length; j++) {
        var schedule = array[j];
        if(weeklists[i].dataset["timestamp"] === schedule["ymd"]){
          var a_link = document.createElement("a");
          a_link.classList.add("listcol");
          var dd_sttime = document.createElement("dd");
          var dd_sckb = document.createElement("dd");
          var dd_kjnam = document.createElement("dd");
    
          var sttime = schedule["sttime"].trim();
          if(sttime == "" || sttime == "0000"){
            sttime = "";
          } else if(schedule["filmekbn"] === "11" && sttime.substring(0, 3) === "233"){
            var no = sttime.substring(3);
            sttime = kate_nos[no];
          } else {
            var sttime_hh = sttime.substring(0, 2);
            var sttime_mm = sttime.substring(2);
            var sttime = sttime_hh + ":" + sttime_mm;
          }

          if(schedule["ordno"]){
            var ordno = schedule["ordno"];
          } else {
            var ordno = "";
          }
    
          if(schedule["syno"]){
            var syno = schedule["syno"];
          } else {
            var syno = "";
          }

          if(schedule["sjtrei"]){
            var sjtrei = schedule["sjtrei"];
          } else {
            var sjtrei = "";
          }
    
          var url_param = 'details.php?ymd=' + schedule["ymd"] + '&kancd=' + schedule["kancd"] + '&ordno=' + ordno + '&syno=' + syno + '&filmekbn=' + schedule["filmekbn"] + '&sjtrei=' + sjtrei;
          a_link.setAttribute('href', url_param);
      
          dd_sttime.innerHTML = sttime;
          for (let k = 0; k < week_sckbs.length; k++) {
            var week_sckb = week_sckbs[k];
            if(week_sckb["filmekbn"] === schedule["filmekbn"]){
              var sckb = week_sckb["sckb"];
            }
          }
          dd_sckb.innerHTML = sckb;
          dd_kjnam.innerHTML = schedule["kjnam"];
    
          switch(schedule["filmekbn"]) {
            case "11":
              a_link.classList.add("bl_categorycolor__fkb11");
              break;
            case "0":
              a_link.classList.add("bl_categorycolor__fkb0");
              break;
            case "30":
            case "31":
            case "32":
              a_link.classList.add("bl_categorycolor__fkb30-32");
              break;
            case "33":
            case "34":
              a_link.classList.add("bl_categorycolor__fkb33-34");
              break;
            case "36":
              a_link.classList.add("bl_categorycolor__fkb36");
              break;
            case "14":
              a_link.classList.add("bl_categorycolor__fkb14");
              break;
            case "44":
              a_link.classList.add("bl_categorycolor__fkb44");
              break;
            case "51":
              a_link.classList.add("bl_categorycolor__fkb51");
              break;
            default:
              a_link.classList.add("bl_categorycolor__gray");
          }

          a_link.classList.add("d-flex");
          a_link.classList.add("bl_kandata");
          a_link.classList.add("justify-content-between");
      
          a_link.appendChild(dd_sttime);
          a_link.appendChild(dd_sckb);
          a_link.appendChild(dd_kjnam);
          weeklists[i].appendChild(a_link);
        }
      }
    }

    return weeklists[i];
  }
}

{
  function createMonthList(array) {
    var monthlists = document.querySelectorAll('.js_monthlist');
    var kate_nos = ['①','②','③','④','⑤','⑥','⑦','⑧','⑨','⑩'];
    var week_sckbs = [{"filmekbn": "0", "sckb": "オペ"}, {"filmekbn": "11", "sckb": "カテ"}, {"filmekbn": "14", "sckb": "機器"}, {"filmekbn": "30", "sckb": "上内"}, {"filmekbn": "31", "sckb": "下内"}, {"filmekbn": "32", "sckb": "気管内"}, {"filmekbn": "33", "sckb": "ERCP"}, {"filmekbn": "34", "sckb": "胃婁"}, {"filmekbn": "36", "sckb": "病状"}, {"filmekbn": "44", "sckb": "ペ"}, {"filmekbn": "51", "sckb": "化学"}];
    for (var i = 0; i < monthlists.length; i++ ){
      monthlists[i].innerHTML = "";
      for (var j = 0; j < array.length; j++) {
        var schedule = array[j];
        if(monthlists[i].dataset["timestamp"] === schedule["ymd"]){
          var a_link = document.createElement("a");
          a_link.classList.add("listcol");
          var dd_sttime = document.createElement("dd");
          var dd_sckb = document.createElement("dd");
          var dd_kjnam = document.createElement("dd");
    
          var sttime = schedule["sttime"].trim();
          if(sttime == "" || sttime == "0000"){
            sttime = "";
          } else if(schedule["filmekbn"] === "11" && sttime.substring(0, 3) === "233"){
            var no = sttime.substring(3);
            sttime = kate_nos[no];
          } else {
            var sttime_hh = sttime.substring(0, 2);
            var sttime_mm = sttime.substring(2);
            var sttime = sttime_hh + ":" + sttime_mm;
          }

          if(schedule["ordno"]){
            var ordno = schedule["ordno"];
          } else {
            var ordno = "";
          }
    
          if(schedule["syno"]){
            var syno = schedule["syno"];
          } else {
            var syno = "";
          }

          if(schedule["sjtrei"]){
            var sjtrei = schedule["sjtrei"];
          } else {
            var sjtrei = "";
          }
    
          var url_param = 'details.php?ymd=' + schedule["ymd"] + '&kancd=' + schedule["kancd"] + '&ordno=' + ordno + '&syno=' + syno + '&filmekbn=' + schedule["filmekbn"] + '&sjtrei=' + sjtrei;
          a_link.setAttribute('href', url_param);
      
          dd_sttime.innerHTML = sttime;
          for (let k = 0; k < week_sckbs.length; k++) {
            var week_sckb = week_sckbs[k];
            if(week_sckb["filmekbn"] === schedule["filmekbn"]){
              var sckb = week_sckb["sckb"];
            }
          }
          dd_sckb.innerHTML = sckb;
          dd_kjnam.innerHTML = schedule["kjnam"];
    
          switch(schedule["filmekbn"]) {
            case "11":
              a_link.classList.add("bl_categorycolor__fkb11");
              break;
            case "0":
              a_link.classList.add("bl_categorycolor__fkb0");
              break;
            case "30":
            case "31":
            case "32":
              a_link.classList.add("bl_categorycolor__fkb30-32");
              break;
            case "33":
            case "34":
              a_link.classList.add("bl_categorycolor__fkb33-34");
              break;
            case "36":
              a_link.classList.add("bl_categorycolor__fkb36");
              break;
            case "14":
              a_link.classList.add("bl_categorycolor__fkb14");
              break;
            case "44":
              a_link.classList.add("bl_categorycolor__fkb44");
              break;
            case "51":
              a_link.classList.add("bl_categorycolor__fkb51");
              break;
            default:
              a_link.classList.add("bl_categorycolor__gray");
          }

          a_link.classList.add("d-flex");
          a_link.classList.add("bl_kandata");
          a_link.classList.add("justify-content-between");
      
          a_link.appendChild(dd_sttime);
          a_link.appendChild(dd_sckb);
          a_link.appendChild(dd_kjnam);
          monthlists[i].appendChild(a_link);
        }
      }
    }

    return monthlists[i];
  }
}

{
  var filmekbn_tabs = document.querySelectorAll(".js_filmekbn_tab")
  for (var i = 0; i < filmekbn_tabs.length; i++) {
    var filmekbn_tab = filmekbn_tabs[i];
    filmekbn_tab.addEventListener("click", function(e){
      var filmekbn = e.target.dataset["value"];
      if(filmekbn === "all"){
        if(list_name === "week"){
          createWeekList(schedule_array_all);
        } else if(list_name === "month"){
          createMonthList(schedule_array_all);
        } else {
          createDayList(schedule_array_all);
        }
      } else {
        changeArray(filmekbn);
      }
      
      var click_target = e.target;
      click_target.classList.add("active");
      for(var j = 0; j < filmekbn_tabs.length; j++){
        var element = filmekbn_tabs[j];
        if(element !== click_target){
          element.classList.remove("active");
        }
      }
    })
  }
}


{
  if(list_name === "week"){
    window.onload = createWeekList(array);
  } else if (list_name === "month"){
    window.onload = createMonthList(array);
  } else {
    window.onload = createDayList(schedule_array_all);
  }
  
}

{
  var calendar_btn = document.querySelector(".js_md-calendar-btn");
  var md_calender = document.querySelector(".js_md-calendar");
  var screen = document.querySelector(".js_screen");
  calendar_btn.addEventListener("click", function () {
    md_calender.classList.toggle("active");
    screen.classList.toggle("active");
  })
}