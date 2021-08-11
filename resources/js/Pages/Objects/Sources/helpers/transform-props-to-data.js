export const transformPropsToData = (properties) => {
  const data = {};

  for (const property of properties) {
    const inputType = property.property.inputType;

    if (property.property) {
      const placeholder = inputType === "select" ? {} : "";

      const key = property.property.symbolic_name;

      data[key] = property.property.default_value ?? placeholder;
    }
  }

  return data;
};
