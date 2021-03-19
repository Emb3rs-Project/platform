import { computed } from 'vue'

export default function useUniqueLocations(locations) {
    const uniqueLocations = computed(() => {
        return [...new Map(
            locations.map(item => {
                if (item.data) {
                    return [item.location_id, item];
                }
            })
        ).values()];
    });

    return uniqueLocations;
};
