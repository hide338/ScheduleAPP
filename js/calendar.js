{
  /**
   * カレンダーを作る
   */

  // 曜日を配列で定義
  var week = ["日", "月", "火", "水", "木", "金", "土"];
  // 今日の日付を取得
  var today = new Date();
  // カレンダーの作るための基準を月の初めに固定
  var showDateCalendar = new Date(today.getFullYear(), today.getMonth(), 1);

  // ウィンドウが読み込まれたときにカレンダーを作る
  window.onload = showCalendar(showDateCalendar);

  // 表示付きの前月を表示するための関数
  function prevMonth() {
    showDateCalendar.setMonth(showDateCalendar.getMonth() - 1);
    showCalendar(showDateCalendar);
  }
  document.querySelector(".prevMonth").addEventListener("click", function(){
    prevMonth();
  })

  // 表示付きの次月を表示するための関数
  function nextMonth() {
    showDateCalendar.setMonth(showDateCalendar.getMonth() + 1);
    showCalendar(showDateCalendar);
  }
  document.querySelector(".nextMonth").addEventListener("click", function(){
    nextMonth();
  })

  // 選択された日のカレンダーを表示するための関数
  function showCalendar(date) {
    // 選択された日付の年を変数に格納
    var year = date.getFullYear();
    // 選択された日付の月を変数に格納
    var month = date.getMonth();
    // カレンダーの日付表示をする
    document.querySelector(".js_calendarShowYM").innerHTML = year + "年" + (month + 1) + "月";
    // cleateCalendar関数を起動して、カレンダーを作成する
    document.querySelector(".js_calendar").innerHTML = cleateCalendar(year, month);
  }

  // カレンダーを作成するための関数
  function cleateCalendar(year, month) {
    // カレンダー作成するためのHTMLを作成して変数に格納
    var calendar = "<table class='bl_table bl_calendar'><thead><tr>";
    // カレンダーの曜日を表示する
    for(var i = 0; i < week.length; i++){
      calendar += "<th>" + week[i] + "</th>";
    }
    // カレンダー関数にHTMLを追加
    calendar += "</tr></thead>";

    // カレンダーのカウントを定期する
    var count = 0;
    // 選択月の1日の曜日を取得
    var startDayOfWeek = new Date(year, month, 1).getDay();
    // 選択月の最終日を取得
    var endDate = new Date(year, month + 1, 0).getDate();
    // 選択月の前月の最終日を取得
    var lastMonthEndDate = new Date(year, month, 0).getDate();
    // カレンダーの行数を作成する
    var row = Math.ceil((startDayOfWeek + endDate) / week.length);

    // カレンダー関数にHTMLを追加
    calendar += "<tbody>"
    // カレンダーの行数分ループを回す
    for (var i = 0; i < row; i++){
      calendar += "<tr>";
      // カレンダーの列を曜日の数分ループで回す
      for(var j = 0; j < week.length; j++){
        // 前月の日付(i === 0 && j < startDayOfWeek)、次月の日付(count >= endDate)、当月の日付をif文で条件分岐して表示する
        if(i === 0 && j < startDayOfWeek){
          var dateValue = new Date(year, month - 1, (lastMonthEndDate - startDayOfWeek + j + 1));
          var valueYear = dateValue.getFullYear();
          var valueYear = String(valueYear);
          var valueMonth = dateValue.getMonth() + 1;
          var valueMonth = String(valueMonth);
          if(valueMonth.length === 1){
            var valueMonth = "0" + valueMonth;
          } else {
            valueMonth;
          }
          var valueDay = dateValue.getDate();
          var valueDay = String(valueDay);
          if(valueDay.length === 1){
            var valueDay = "0" + valueDay;
          } else {
            valueDay;
          }
          var value = valueYear + valueMonth + valueDay;
          calendar += "<td class='disabled' data-value='" + value +"'>" + (lastMonthEndDate - startDayOfWeek + j + 1) + "</td>";
        } else if(count >= endDate){
          count ++;
          var dateValue = new Date(year, month, count);
          var valueYear = dateValue.getFullYear();
          var valueYear = String(valueYear);
          var valueMonth = dateValue.getMonth() +1 ;
          var valueMonth = String(valueMonth);
          if(valueMonth.length === 1){
            var valueMonth = "0" + valueMonth;
          } else {
            valueMonth;
          }
          var valueDay = dateValue.getDate();
          var valueDay = String(valueDay);
          if(valueDay.length === 1){
            var valueDay = "0" + valueDay;
          } else {
            valueDay;
          }
          var value = valueYear + valueMonth + valueDay;
          calendar += "<td class='disabled' data-value='" + value + "'>" + (count - endDate) + "</td>";
        } else {
          count++;
          if(year == today.getFullYear() && month == today.getMonth() && count == today.getDate()){
            var dateValue = today;
            var valueYear = dateValue.getFullYear();
            var valueYear = String(valueYear);
            var valueMonth = dateValue.getMonth() + 1;
            var valueMonth = String(valueMonth);
            if(valueMonth.length === 1){
              var valueMonth = "0" + valueMonth;
            } else {
              valueMonth;
            }
            var valueDay = dateValue.getDate();
            var valueDay = String(valueDay);
            if(valueDay.length === 1){
              var valueDay = "0" + valueDay;
            } else {
              valueDay;
            }
            var value = valueYear + valueMonth + valueDay;
            calendar += "<td class='today js_selectDate' data-value='" + value + "'>" + count + "</td>";
          } else {
            var dateValue = new Date(year, month, count);
            var valueYear = dateValue.getFullYear();
            var valueYear = String(valueYear);
            var valueMonth = dateValue.getMonth() + 1;
            var valueMonth = String(valueMonth);
            if(valueMonth.length === 1){
              var valueMonth = "0" + valueMonth;
            } else {
              valueMonth;
            }
            var valueDay = dateValue.getDate();
            var valueDay = String(valueDay);
            if(valueDay.length === 1){
              var valueDay = "0" + valueDay;
            } else {
              valueDay;
            }
            var value = valueYear + valueMonth + valueDay;
            calendar += "<td class='js_selectDate' data-value='" + value + "'>" + count + "</td>";
          }
        } 
      }
      calendar += "</tr>";
    }
    calendar += "</tbody></table>";
    return calendar;
  }

  // var calendar = document.querySelector(".js_calendar");
  // var todayButton = document.createElement("button");
  // todayButton.classList.add("btn-outline-primary");
  // todayButton.classList.add("el_today");
  // todayButton.classList.add("js_today")
  // todayButton.innerHTML = "today";
  // calendar.appendChild(todayButton);
  // document.querySelector(".js_today").addEventListener("click", function(){
  //   showDateCalendar = new Date(today.getFullYear(), today.getMonth(), today.getDate());
  //   showCalendar(showDateCalendar);
  // })
}
