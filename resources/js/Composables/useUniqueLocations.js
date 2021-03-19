import { computed } from 'vue'

export default function useUniqueLocations(locations) {
    const uniqueLocations = computed(() => {
        return [...new Map(
            locations.flatMap(item => {
                if (item.location_id) return [[item.location_id, item]];
                if (item.geo_object_id) return [[item.geo_object_id, item]];
                return [];
            })
        ).values()];
    });

    return uniqueLocations;
};
