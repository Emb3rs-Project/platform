const filterOptions = [
    {
        title: "Sources",
        description:
            "Display all the Sources for the currently selected Institution",
        paths: {
            create: "objects.sources.create",
            edit: "objects.sources.edit",
            details: "objects.sources.show",
            delete: "objects.sources.delete"
        },
        current: true,
    },
    {
        title: "Sinks",
        description:
            "Display all the Sinks for the currently selected Institution",
        paths: {
            create: "objects.sinks.create",
            edit: "objects.sinks.edit",
            details: "objects.sinks.show",
            delete: "objects.sinks.delete"
        },
        current: false,
    },
    {
        title: "Links",
        description:
            "Display all the Links for the currently selected Institution",
        paths: {
            create: "objects.links.create",
            edit: "objects.links.edit",
            details: "objects.links.show",
            delete: "objects.links.delete"
        },
        current: false,
    },
];

export {
    filterOptions
}
