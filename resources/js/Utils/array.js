const keyParToSelect = (_array = [], { id = 'id', name = 'name' } = {}) => _array.map((t) => ({
    key: t[id],
    value: t[name],
}))





export {
    keyParToSelect
}
