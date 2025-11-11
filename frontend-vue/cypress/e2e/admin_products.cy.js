describe("Admin products E2E", () => {
    const api = "http://127.0.0.1:8100";

    it("cria categoria + produto via API, verifica na UI e remove ambos", () => {
        const sku = "e2e-" + Date.now();

        // Criar categoria necessária para o produto
        cy.request({
            method: "POST",
            url: `${api}/api/admin/categories`,
            body: { name: "E2E Prod Cat", active: true },
            failOnStatusCode: false,
        }).then((catRes) => {
            expect([200, 201]).to.include(catRes.status);
            const catId = catRes.body?.id;

            // Criar produto usando a categoria criada
            cy.request({
                method: "POST",
                url: `${api}/api/admin/products`,
                body: {
                    name: "E2E Product",
                    description: "Produto criado por Cypress",
                    price: 9.99,
                    category_id: catId,
                    stock: 10,
                    sku,
                    status: "active",
                    images: ["/storage/test.jpg"],
                },
                failOnStatusCode: false,
            }).then((prodRes) => {
                expect([200, 201]).to.include(prodRes.status);
                const prodId = prodRes.body?.id;

                // Visitar página de produtos admin e procurar pelo produto
                cy.visit("/admin/produtos");
                cy.contains("E2E Product", { timeout: 5000 }).should("exist");

                // Limpeza: deletar produto e categoria
                if (prodId) {
                    cy.request("DELETE", `${api}/api/admin/products/${prodId}`)
                        .its("status")
                        .should("eq", 200);
                }
                if (catId) {
                    cy.request("DELETE", `${api}/api/admin/categories/${catId}`)
                        .its("status")
                        .should("eq", 200);
                }
            });
        });
    });
});
