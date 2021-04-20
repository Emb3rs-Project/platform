const useSlideOver = {
    computed: {
        slideOverComponent() {
            return this.$page.props.slideOver ?
                () => import(`@/Pages/${this.$page.props.slideOver}`)
                : false
        },
        slideOverProps() {
            return this.$page.props.slideOverProps
        }
    }
}


export { useSlideOver }
