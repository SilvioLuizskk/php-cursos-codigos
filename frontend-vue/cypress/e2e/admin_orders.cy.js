describe("Admin orders page smoke", () => {
    it("abre a página de pedidos e verifica cabeçalho (smoke)", () => {
        cy.visit("/admin/pedidos");
        cy.contains("Gerenciar Pedidos", { timeout: 5000 }).should("exist");
        // Verifica títulos em pt-BR usados pela tabela
        cy.contains("Pedido").should("exist");
        cy.contains("Valor").should("exist");
    });
});
