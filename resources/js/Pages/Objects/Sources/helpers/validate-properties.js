export const validateProperies = (parent, properties, errors) => {
  for (const property of properties) {
    const propertyErrors = [];

    const inputType = property.property.inputType;
    const dataType = property.property.dataType;
    const symbolicName = property.property.symbolic_name;
    const value = parent.data[property.property.symbolic_name];
    const propertyName = property.property.name.toLowerCase();

    let propertyCopy = value;

    if (inputType === "select") {
      // if the property has a value, get it and re-assign the property as a string
      if (Object.keys(value).length) {
        propertyCopy = value.key;
      } else {
        propertyCopy = "";
      }
    }

    if (property.required) {
      if (!propertyCopy)
        propertyErrors.push(`The ${propertyName} field is required.`);
    }

    switch (dataType.toLowerCase()) {
      case "text":
        if (typeof propertyCopy !== "string")
          propertyErrors.push(
            `The ${propertyName} field must be a string.`
          );
        break;
      case "string":
        if (typeof propertyCopy !== "string")
          propertyErrors.push(
            `The ${propertyName} field must be a string.`
          );
        break;
      case "number":
        if (isNaN(propertyCopy))
          propertyErrors.push(
            `The ${propertyName} field must be numeric.`
          );
        break;
      case "float":
        if (isNaN(propertyCopy))
          propertyErrors.push(
            `The ${propertyName} field must be numeric.`
          );
        break;
      case "datetime":
        // TODO: validate the datetime
        break;

      default:
        break;
    }

    if (propertyErrors.length)
      errors[`${parent}.${symbolicName}`] = propertyErrors;
  }
};
