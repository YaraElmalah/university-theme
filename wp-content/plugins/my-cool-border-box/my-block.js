/**
 * yara/border-box namespace/blockname (We do that to prevent any conflict with anyother plugin)
 * React ==> we need to change it with wp.element ==> to get the library that the wordpress default library
 * instead of using babel.js, we can use libraties that we use libs, but we need experience on (npm, cli)
 * if we wrote on the console wp.components (we got all the components that Gutenberg provide)
 */

wp.blocks.registerBlockType("yara/border-box", {
  title: "My Cool Border Box",
  icon: "smiley", //Dashicons
  category: "common",
  attributes: { content: { type: "string" }, color: { type: "string" } }, //our attributes (fields on the block)
  /**
   * We would use babel.js to use jsx to convert html into React
   */
  edit: function (props) {
    //backend
    function updateContent(event) {
      props.setAttributes({ content: event.target.value });
    }
    function updateColor(value) {
      props.setAttributes({ color: value.hex });
    }
    return React.createElement(
      "div",
      null,
      React.createElement("h3", null, "Your Cool Border Box"),
      React.createElement("input", {
        type: "text",
        value: props.attributes.content,
        onChange: updateContent,
      }),
      /*#__PURE__*/ React.createElement(wp.components.ColorPicker, {
        color: props.attributes.color,
        onChangeComplete: updateColor,
      })
    );
  },
  save: function (props) {
    //frontend
    return React.createElement(
      "h3",
      {
        style: {
          border: "5px solid " + props.attributes.color,
        },
      },
      props.attributes.content
    );
  },
});
