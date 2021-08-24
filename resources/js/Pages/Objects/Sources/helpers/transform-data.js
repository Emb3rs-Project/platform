export const transformData = (data, templateProperties) => {
  const transformedData = {};

  for (const key in data) {
    if (!Object.hasOwnProperty.call(data, key)) continue;

    const templateProperty = templateProperties.find(
      (templateProperty) => templateProperty.property.symbolic_name === key
    );

    if (!templateProperty) continue;

    if (templateProperty.property.inputType === "select") {
      if (!data[key]) transformedData[key] = {};

      const options = templateProperty.property.data.options;

      for (const option in options) {
        if (options[option].key === data[key]) {
          transformedData[key] = {
            key: options[option].key,
            value: options[option].value,
          };

          break;
        }
      }
    } else {
      transformedData[key] = data[key];
    }
  }

  return transformedData;
};
