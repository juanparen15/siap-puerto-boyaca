import { animate, inView, stagger } from 'motion'

document.addEventListener('DOMContentLoaded', () => {

    // ─── Hero stat cards ──────────────────────────────────────────────────────
    const statCards = document.querySelectorAll('.stat-card')
    if (statCards.length) {
        animate(statCards, { opacity: 0, y: 28 }, { duration: 0 })
        inView('.stat-card', () => {
            animate(statCards, { opacity: 1, y: 0 }, {
                delay: stagger(0.12),
                duration: 0.6,
                easing: [0.22, 1, 0.36, 1],
            })
        }, { margin: '0px 0px -40px 0px' })
    }

    // ─── Quick access cards ───────────────────────────────────────────────────
    const glassCards = document.querySelectorAll('.glass-card')
    if (glassCards.length) {
        animate(glassCards, { opacity: 0, y: 36 }, { duration: 0 })
        inView('.glass-card', () => {
            animate(glassCards, { opacity: 1, y: 0 }, {
                delay: stagger(0.13),
                duration: 0.6,
                easing: [0.22, 1, 0.36, 1],
            })
        }, { margin: '0px 0px -60px 0px' })
    }

    // ─── Feature items (About SIAP) ───────────────────────────────────────────
    const featureItems = document.querySelectorAll('.feature-item')
    if (featureItems.length) {
        animate(featureItems, { opacity: 0, x: -24 }, { duration: 0 })
        featureItems.forEach((el, i) => {
            inView(el, ({ target }) => {
                animate(target, { opacity: 1, x: 0 }, {
                    delay: i * 0.15,
                    duration: 0.55,
                    easing: [0.22, 1, 0.36, 1],
                })
            })
        })
    }

    // ─── Section headings ─────────────────────────────────────────────────────
    document.querySelectorAll('.section-heading').forEach(el => {
        animate(el, { opacity: 0, y: 14 }, { duration: 0 })
        inView(el, ({ target }) => {
            animate(target, { opacity: 1, y: 0 }, {
                duration: 0.5,
                easing: 'ease-out',
            })
        })
    })

    // ─── Generic scroll-reveal blocks ─────────────────────────────────────────
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        animate(el, { opacity: 0, y: 20 }, { duration: 0 })
        inView(el, ({ target }) => {
            animate(target, { opacity: 1, y: 0 }, {
                duration: 0.5,
                easing: [0.22, 1, 0.36, 1],
            })
        })
    })

    // ─── Scroll indicator bounce (landing hero) ───────────────────────────────
    const scrollHint = document.querySelector('.scroll-hint')
    if (scrollHint) {
        animate(
            scrollHint,
            { y: [0, 8, 0] },
            { duration: 1.4, repeat: Infinity, easing: 'ease-in-out' }
        )
    }
})
