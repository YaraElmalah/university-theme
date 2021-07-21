(function ($) {
  class search {
    //1-Describe and create an object
    constructor() {
      //Add search markup
      this.addSearchHtml();
      this.openButton = $(".js-search-trigger");
      this.closeButton = $(".search-overlay__close");
      this.searchOverlay = $(".search-overlay");
      this.searchField = $(".search-term");
      //overlay properties (false == not open, true == is open)
      this.isOverlayOpen = false;
      //for typing logic
      this.typingTimer;
      //spinner visibility
      this.spinnerVisibility = false;
      //Resulting Div Result
      this.resultDiv = $(".search-overlay--results");
      //the previous value inside search input
      this.previousValue;
      //trigger the events
      this.events();
    }
    //2-Events
    events() {
      this.openButton.on("click", this.openOverlay.bind(this));
      this.closeButton.on("click", this.closeOverlay.bind(this));
      //	note: keydown is faster than keyup
      $(document).on("keydown", this.keyPressDispatcher.bind(this));
      this.searchField.on("keyup", this.typingLogic.bind(this));
    }

    //3-Methods
    openOverlay() {
      this.searchOverlay.addClass("search-overlay--active");
      $("body").addClass("body-no-scroll");
      //make the field focused once the search is opened
      setTimeout(() => this.searchField.focus(), 301);
      this.searchField.val("");
      this.isOverlayOpen = true;
    }
    closeOverlay() {
      this.searchOverlay.removeClass("search-overlay--active");
      $("body").removeClass("body-no-scroll");
      this.isOverlayOpen = false;
    }
    keyPressDispatcher(e) {
      //clicking on "s" on keyboard opening the search window.
      //we do the focus on input or textarea part to prevent the conflict between the search input and any focusable element in the page
      if (
        e.keyCode === 83 &&
        this.isOverlayOpen === false &&
        !$("input, textarea").is(":focus")
      ) {
        this.openOverlay();
        this.isOverlayOpen = true;
      }
      //clicking on "esc" on keyboard close the search window
      if (e.keyCode === 27 && this.isOverlayOpen === true) {
        this.closeOverlay();
        this.isOverlayOpen = false;
      }
    }
    typingLogic() {
      //here we are going to set an only setTimeOut so it wouldn't repeat itself
      clearTimeout(this.typingTimer);
      if (
        this.spinnerVisibility === false &&
        this.searchField.val() !== this.previousValue
      ) {
        this.resultDiv.html('<div class="spinner-loader"></div>');
        this.spinnerVisibility = true;
        this.previousValue = this.searchField.val();
      }
      this.typingTimer = setTimeout(this.getResults.bind(this), 750);
    }
    getResults() {
      //we are going to do the request here
      $.getJSON(
        uni_info.root_url +
          "/wp-json/university/v1/search?term=" +
          this.searchField.val(),
        (results) => {
          this.resultDiv.html(`
					<div class="row">
						<div class="one-third">
							<h2 class="section-overlay__section-title">General Information</h2>
					${
            results.general.length
              ? `<ul class="link-list min-list">
						${results.general
              .map(
                (item) =>
                  `<li><a href="${item.permalink}">${item.title}</a> ${
                    item.type === "post" ? `By ${item.authorName}` : ""
                  } </li>`
              )
              .join("")}</ul>`
              : `<p> There is no Posts or Pages mathched your search</p>`
          }
						</div>
						<div class="one-third">
							<h2 class="section-overlay__section-title" >Professors</h2>
				${
          results.profs.length
            ? `<ul class="professor-cards">
		      	${results.profs
              .map(
                (item) =>
                  `<li class="professor-card__list-item">
               <a href="${item.permalink}" class="professor-card">
              <img class="professor-card__image" src="${item.thumbnail}">
              <span class="professor-card__name">${item.title}</span>
              </a>
            </li>`
              )
              .join("")}</div>`
            : "<p> There is no Professors matched your search</p>"
        }
						</div>	
						<div class="one-third">
							<h2 class="section-overlay__section-title">Programs</h2>
				${
          results.programs.length
            ? `<ul class="link-list min-list">
		      	${results.programs
              .map(
                (item) =>
                  `<li><a href="${item.permalink}">${item.title}</a> ${
                    item.type === "post" ? `By ${item.authorName}` : ""
                  } </li>`
              )
              .join("")}</ul>`
            : `<p> There is no Programs matched your search <a href="${
                uni_info.root_url + "/programs/"
              }">View All Programs</a> </p>`
        }
						</div>					
						<div class="one-third">
							<h2 class="section-overlay__section-title">Events</h2>
				${
          results.events.length
            ? `<div>
		      	${results.events
              .map(
                (item) =>
                  `<div class="event-summary">
          <a class="event-summary__date t-center" href="${item.permalink}">
            <span class="event-summary__month">${item.month}</span>
            <span class="event-summary__day">${item.day}</span>  
          </a>
          <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
            <p>${item.desc}</p>
          </div>
        </div>`
              )
              .join("")}</ul>`
            : "<p> There is no Events matched your search</p>"
        }
						</div>
					</div>
					`);
          this.spinnerVisibility = false;
        }
      );
      /*$.when(
			$.getJSON(uni_info.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val())
			 , 
		    $.getJSON(uni_info.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val())
			 ).then(
			 (posts, pages) => {
			 		//merge 2 arrays
				var combinedResults = posts[0].concat(pages[0]);
				//${} => not related to jQuery
				//join used to convert array into a string
				this.resultDiv.html(`
						<h2 class="section-overlay__section-title">General Information</h2>
						${combinedResults.length ? `<ul class="link-list min-list">
						${combinedResults.map(item=>
								`<li><a href="${item.link}">${item.title.rendered}</a> ${item.type === 'post' ? `By ${item.authorName}` : ''} </li>`
							).join('')}</ul>`
								 :
							 `<p> There is no  information about this section </p>`

							}
					`);
				 this.spinnerVisibility = false;
				 //Error Handling
			 }, () => {
			 	this.resultDiv.html("<p>Unexpected Error .. Try Again</p>");
			 	 this.spinnerVisibility = false;
			 }
			 
			 );*/
      /*
				posts=>: this is called arrow function
				Ternary Operator => if condition we can put inside html template
				We have 2 types of doing requests: 
				- Synchronous: doing one task, then doing the other
				- Asychronous: Multitasking => doing more than one task in the sametime
			*/
      /*	$.getJSON(uni_info.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val() , posts=> {
			$.getJSON(uni_info.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val() , pages=> {
				//merge 2 arrays
				var combinedResults = posts.concat(pages);
				//${} => not related to jQuery
				//join used to convert array into a string
				this.resultDiv.html(`
						<h2 class="section-overlay__section-title">General Information</h2>
						${combinedResults.length ? `<ul class="link-list min-list">
						${combinedResults.map(item=>
								`<li><a href="${item.link}">${item.title.rendered}</a></li>`
							).join('')}</ul>`
								 :
							 `<p> There is no  information about this section </p>`

							}
					`);
				 this.spinnerVisibility = false;

			} )
		} )*/
    }
    addSearchHtml() {
      $("body").append(`
		  <div class="search-overlay">  
         <div class="search-overlay__top"> 
          <div class="container"> 
              <i class="fa fa-search search-overlay__icon"></i>
              <input type="text" class="search-term" placeholder="What are you looking for?" aria-hidden="true">
              <i class="fa fa-window-close search-overlay__close"></i>
          </div>  
      </div>
      <div class="container"> 
          <div class="search-overlay--results"> 
           </div>
      </div>  
  </div> 
				`);
    }
  }
  //creating the object
  var magicalSearch = new search();
})(jQuery);
