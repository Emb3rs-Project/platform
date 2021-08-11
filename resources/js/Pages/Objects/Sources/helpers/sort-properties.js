export const sortProperties = (properties) => {
  return properties.sort((a, b) =>
    a.order < b.order ? -1 : a.order > b.order ? 1 : 0
  );
};
