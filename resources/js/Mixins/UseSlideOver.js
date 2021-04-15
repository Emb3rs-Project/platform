const useSlideOver = {
    computed: {
        slideOverComponent() {
            return this.$page.props.slideOver ?
                () => import(`@/Pages/${this.$page.props.slideOver}`)
                : false
        }
    }
}


export { useSlideOver }
