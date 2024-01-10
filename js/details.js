{
  var kinki_tab = document.querySelector(".js_kinki-tab");
  var kansen_table = document.querySelector(".js_kansen-table");
  var kinki_table = document.querySelector(".js_kinki-table");

  kinki_tab.addEventListener("click", function(){
    kansen_table.classList.add("hidden");
    kinki_table.classList.remove("hidden");
    kinki_tab.classList.add("tab-active");
    kansen_tab.classList.remove("tab-active");
  })

  var kansen_tab = document.querySelector(".js_kansen-tab");
  kansen_tab.addEventListener("click", function(){
    kinki_table.classList.add("hidden");
    kansen_table.classList.remove("hidden");
    kansen_tab.classList.add("tab-active");
    kinki_tab.classList.remove("tab-active");
  })

}