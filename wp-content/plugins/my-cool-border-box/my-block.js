/**
 * yara/border-box namespace/blockname (We do that to prevent any conflict with anyother plugin)
 * React ==> we need to change it with wp.element ==> to get the library that the wordpress default library
 */
wp.blocks.registerBlockType("yara/border-box", {
  title: "My Cool Border Box",
  icon: "smiley", //Dashicons
  category: "common",
  attributes: { content: { type: "string" }, color: { type: "string" } }, //our attributes (fields on the block)
  /**
   * We would use babel.js to use jsx to convert html into React
   */
  edit: function () {
    //backend
    return React.createElement(
      "div",
      null,
      React.createElement("h3", null, "Your Cool Border Box"),
      React.createElement("input", {
        type: "text",
      })
    );
  },
  save: function () {
    //frontend
    return null;
  },
});
