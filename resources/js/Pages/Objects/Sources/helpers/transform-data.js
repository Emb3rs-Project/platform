export const transformData = (data, templateProperties, equipment = null, selectedEquipment = null) => {
  const transformedData = {};

  for (const key in data) {
    if (!Object.hasOwnProperty.call(data, key)) continue;

    const templateProperty = templateProperties.find(
      (templateProperty) => templateProperty.property.symbolic_name === key
    );

    if (!templateProperty) continue;

    if (!data[key]) {
      transformedData[key] = templateProperty.property.inputType === "select" ? {} : '';

      continue
    }

    if (templateProperty.property.inputType === "select") {
      let element = typeof(data[key]) == 'object' ? data[key].key : data[key];
      if (key !== "equipment_selected") {
        const options = templateProperty.property.data.options;

        for (const option in options) {
          if (options[option].key === element) {
            transformedData[key] = {
              key: options[option].key,
              value: options[option].value,
            };

            break;
          }
        }
      } else if(equipment) {
        for (const item of equipment) {
          let option = selectedEquipment.find((e) => e.identify && e.identify == element);
          if (typeof(option) == 'object') {
            transformedData[key] = {
              key: element,
              value: `${item.name} | ${option.data['name']}`,
            };
          } else {
            transformedData[key] = {};
          }
        }
      }
    } else {
      transformedData[key] = data[key];
    }
  }

  return transformedData;
};
