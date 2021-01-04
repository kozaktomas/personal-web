const BASE_URL = Cypress.env('APP_BASE_URL')

describe('App test', () => {
    it('Homepage test', () => {
        cy.visit(BASE_URL)
        cy.contains('RESUMÉ').click()
        cy.go('back') // back to homepage
        cy.contains('Computers Are My Thing')
        cy.contains('Tomáš Kozák')
        cy.contains('programmer')
        cy.contains('09645870')
    });

    it('Resume test', () => {
        cy.visit(BASE_URL)
        cy.contains('RESUMÉ').click()
        cy.url().should('include', '/resume')
        cy.contains('PHP')
    });

    it('Contact test', () => {
        cy.visit(BASE_URL)
        cy.contains('CONTACT').click()
        cy.url().should('include', '/contact')

        cy.get('input[name="name"]').type('My awesome name')
        cy.get('textarea[name="content"]').type('This is my message')
        cy.get('input[name="captcha"]').type('666')

        cy.contains('SEND MESSAGE').click()
        cy.contains('Your result is incorrect')
    });

    it('My setup test', () => {
        cy.visit(BASE_URL)
        cy.contains('MY SETUP').click()
        cy.url().should('include', '/my-setup')

        cy.contains('Software')
        cy.contains('Google Chrome')
        cy.contains('Spotify')
        cy.contains('Dash')
        cy.contains('Slack')
        cy.contains('Postman')
    });

    it('Talks test', () => {
        cy.visit(BASE_URL)
        cy.contains('TALKS').click()
        cy.url().should('include', '/talks')

        cy.contains('TALKS')
        cy.contains('MONITORING INTRODUCTION')
        cy.contains('Source code')
        cy.contains('Slides')
    });

});
