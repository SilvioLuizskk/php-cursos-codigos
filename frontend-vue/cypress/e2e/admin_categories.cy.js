describe("Admin categories E2E", () => {
    const apiUrl = "http://127.0.0.1:8100";

    it("cria categoria via API, verifica na UI e remove", () => {
        cy.request({
            method: "POST",
            url: `${apiUrl}/api/admin/categories`,
            body: {
                name: "E2E Category",
                description: "Categoria criada por Cypress",
                image: `${apiUrl}/storage/test.jpg`,
                active: true,
            },
            failOnStatusCode: false,
        }).then((res) => {
            expect([200, 201]).to.include(res.status);

            const id = res.body?.id;

            // Visitar a página de admin de categorias e procurar pelo nome
            cy.visit("/admin/categorias");
            cy.contains("E2E Category", { timeout: 5000 }).should("exist");

            // Limpeza: deletar via API
            if (id) {
                cy.request("DELETE", `${apiUrl}/api/admin/categories/${id}`)
                    .its("status")
                    .should("eq", 200);
            }
        });
    });
});
