describe("Admin banners E2E", () => {
    const apiUrl = "http://127.0.0.1:8100";

    it("cria banner via API, verifica na UI de admin e remove", () => {
        // Criar banner via API
        cy.request({
            method: "POST",
            url: `${apiUrl}/api/admin/banners`,
                    body: {
                        title: 'E2E Banner',
                        description: 'Banner criado por Cypress',
                        image: `${apiUrl}/storage/test.jpg`,
                        link: null,
                        position: 'carousel',
                        status: 'active',
                        order: 999
                    },
            failOnStatusCode: false,
        }).then((res) => {
            expect([200, 201]).to.include(res.status);

            const id = res.body?.id;
            // Abrir página de admin de banners
            cy.visit("/admin/banners");

            // Verificar que o título do banner aparece na lista (pode levar um pouco)
            cy.contains("E2E Banner", { timeout: 5000 }).should("exist");

            // Limpeza: deletar via API
            if (id) {
                cy.request("DELETE", `${apiUrl}/api/admin/banners/${id}`)
                    .its("status")
                    .should("eq", 200);
            }
        });
    });
});
