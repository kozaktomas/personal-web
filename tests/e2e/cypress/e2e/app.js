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
        cy.contains('Seznam.cz')
        cy.contains('ShipMonk')
        cy.contains('fulfillment')
        cy.contains('Showmax')
    });

    it('Contact test', () => {
        cy.visit(BASE_URL)
        cy.contains('CONTACT').click()
        cy.url().should('include', '/contact')

        cy.get('input[name="name"]').type('My awesome name')
        cy.get('textarea[name="content"]').type('This is my message')
        cy.get('input[name="captcha"]').type('6666')

        cy.contains('SEND MESSAGE').click()
        cy.contains('Your result is incorrect')
    });

    it('My setup test', () => {
        cy.visit(BASE_URL)
        cy.contains('MY SETUP').click()
        cy.url().should('include', '/my-setup')
    });

    it('Talks test', () => {
        cy.visit(BASE_URL)
        cy.contains('TALKS').click()
        cy.url().should('include', '/talks')

        cy.contains('TALKS')
        cy.contains('MONITORING INTRODUCTION')
        cy.contains('Source code')
        cy.contains('Slides')
        cy.contains('Online stream')
        cy.contains('LIFE WITH PACKAGE MANAGER')
        cy.contains('MONITORING INTRODUCTION 2')
    });

    it('Sitemap XML file', () => {
        cy.request({
            url: BASE_URL + "/sitemap.xml",
            method: "GET",
        })
    });

    it('Robots TXT file', () => {
        cy.visit(BASE_URL + "/robots.txt")
        cy.contains('User-agent: *')
        cy.contains('Allow: /')
    });
});
