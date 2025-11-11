describe("Admin banners UI edit E2E", () => {
    const api = "http://127.0.0.1:8100";

    it("cria banner via API, edita pelo UI, verifica mudança e remove", () => {
        // Criar banner via API
        cy.request("POST", `${api}/api/admin/banners`, {
            title: "E2E Edit Banner",
            description: "Banner para edição via UI",
            image: `${api}/storage/test.jpg`,
            position: "carousel",
            status: "active",
            order: 50,
        }).then((res) => {
            expect([200, 201]).to.include(res.status);
            const id = res.body?.id;

            // Visitar a página de admin de banners
            cy.visit("/admin/banners");

            // Localizar o cartão do banner e clicar em Editar
            cy.contains("E2E Edit Banner", { timeout: 5000 })
                .closest(".border")
                .within(() => {
                    cy.contains("Editar").click();
                });

            // No modal, alterar o título e salvar
            cy.get("input[required]").first().clear().type("E2E Edited Banner");
            // O botão pode estar coberto pelo backdrop; forçar o clique se necessário
            cy.contains("Salvar").click({ force: true });

            // Verificar atualização na lista
            cy.contains("E2E Edited Banner", { timeout: 5000 }).should("exist");

            // Cleanup: deletar via API
            if (id) {
                cy.request("DELETE", `${api}/api/admin/banners/${id}`)
                    .its("status")
                    .should("eq", 200);
            }
        });
    });
});
