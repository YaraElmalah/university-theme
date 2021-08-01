/**
 * yara/border-box namespace/blockname (We do that to prevent any conflict with anyother plugin)
 * React ==> we need to change it with wp.element ==> to get the library that the wordpress default library
 * instead of using babel.js, we can use libraties that we use libs, but we need experience on (npm, cli)
 * if we wrote on the console wp.components (we got all the components that Gutenberg provide)
 * We can put the save method inside the php code creating what called dynamic custom block type
 */

wp.blocks.registerBlockType("yara/border-box", {
  title: "My Cool Border Box",
  icon: "smiley", //Dashicons
  category: "common",
  attributes: {
    content: { type: "string" }, //we should make sure that meta that the js is looking for, exists in the API (we need to register it)
    color: { type: "string" },
  }, //our attributes (fields on the block)
  /**
   * We would use babel.js to use jsx to convert html into React
   */
  edit: function (props) {
    function updateContent(event) {
      props.setAttributes({ content: event.target.value });
    }

    function updateColor(value) {
      props.setAttributes({ color: value.hex });
    }

    return wp.element.createElement(
      "div",
      null,
      wp.element.createElement("h3", null, "Your Cool Border Box"),
      wp.element.createElement("input", {
        type: "text",
        value: props.attributes.content,
        onChange: updateContent,
      }),
      wp.element.createElement(wp.components.ColorPicker, {
        color: props.attributes.color,
        onChangeComplete: updateColor,
      }),
      wp.element.createElement(
        "h3",
        {
          style: {
            color: props.attributes.color,
          },
        },
        "Preview"
      )
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
    // return null; //set this to return false (handle this from backend prespective)
  },
});
