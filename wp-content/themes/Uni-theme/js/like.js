(function ($) {
  class Like {
    constructor() {
      this.events();
    }
    events() {
      $(".like-box").on("click", this.ourClickedDispatcher.bind(this));
    }
    //methods
    ourClickedDispatcher(e) {
      //decide which function to trigger
      /**
       * closest => get the ancestor elements
       */
      var currentLikeBox = $(e.target).closest(".like-box");
      if (currentLikeBox.data("exists") === "yes") {
        this.deleteLike(currentLikeBox);
      } else {
        this.createLike(currentLikeBox);
      }
    }
    createLike(currentLikeBox) {
      let LikedProfessor = currentLikeBox.data("professor");
      $.ajax({
        beforeSend: (xhr) => {
          xhr.setRequestHeader("X-WP-Nonce", likes_info.nonce);
        },
        url: uni_info.root_url + "/wp-json/university/v1/manageLike",
        data: { professorID: LikedProfessor },
        type: "POST",
        success: (response) => {
          console.table(response);
        },
        error: (response) => {
          console.table(response);
        },
      });
    }
    deleteLike(currentLikeBox) {
      $.ajax({
        url: uni_info.root_url + "/wp-json/university/v1/manageLike",
        type: "DELETE",
        success: (response) => {
          console.table(response);
        },
        error: (response) => {
          console.table(response);
        },
      });
    }
  }
  let like = new Like();
})(jQuery);
